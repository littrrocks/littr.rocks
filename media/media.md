## How littr Handles User-Generated Media
First things first, littr depends on [Gumlet](https://github.com/gumlet/php-image-resize) for all of our image needs. Gumlet's library, php-image-resize, will not let us process and resize `MP4`, `JPEG`, `JPG`, you get the idea.

Of course we still check the uploaded file if it's really an image or not.
```php
$check = getimagesize($_FILES["file"]["tmp_name"]);
```
But how do we get around the processing and resizing issue?
### The Short Answer
We don't. We just upload it and resize it when it's echoed.
### The Long Answer
We don't.

We still check the size of the media and extension name.
```php
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "mp4") {
$uploadOk = 0;
}
```
Then, we check to see the file extension and upload it safely.
```php
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

if($imageFileType != "mp4"){

// generate an identifier

$ID = new  IdentifierGeneration(); // custom littr library
$set = $ID->generate_id($length = 50);
$image = new \Gumlet\ImageResize($target_file); // gumlet
$output = $target_dir  .  $set  .  "."  .  $imageFileType;

$image->save($output);
unlink($target_file);

$stmt = $conn->prepare("INSERT  INTO posts (identifier, content, media_path) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_SESSION["identifier"], $_POST['content'], $output);
$stmt->execute();
}else{
$ID = new  IdentifierGeneration();
$set = $ID->generate_id($length = 50);
$output = $target_dir  .  $set  .  "."  .  $imageFileType;
rename($target_file, $output);
$stmt = $conn->prepare("INSERT  INTO posts (identifier, content, media_path) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_SESSION["identifier"], $_POST['content'], $output);
$stmt->execute();
}
```
`if($imageFileType != "mp4")` means if the file is not an .MP4/video, then upload it using Gumlet. `else`/otherwise, just rename the file and upload it.

It's that simple!

P.S, GitHub wouldn't upload the `media/` directory, making it impossible for anyone who wants to download the source code to upload media unless they make the folder themselves. I figure that this is also a way to show the inner-workings of littr, so screw it.
