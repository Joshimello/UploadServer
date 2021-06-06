<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$author = "";
$title = "";
$content = "";
$file = "";

if(isset($_POST['SubmitButton'])){
    $author = $_POST['authorinput'];
    $title = $_POST['titleinput'];
    $text = $_POST['textinput'];

    if(isset($_FILES["fileToUpload"])){
        $file = $_FILES['fileToUpload'];

        $fileName = $_FILES["fileToUpload"]["name"];
        $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];
        $fileSize = $_FILES["fileToUpload"]["size"];
        $fileError = $_FILES["fileToUpload"]["error"];
        $fileType = $_FILES["fileToUpload"]["type"];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if(in_array($fileActualExt, $allowed)){
            if($fileError === 0){
                if($fileSize < 100000000){
                    $fileDestination = 'post/uploads/'.$fileName;
                    move_uploaded_file($fileTmpName, $fileDestination);
        $content = '
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>'.$title.'</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
        </head>
        <body style="font-family: ubuntu;">
            <span>author:<b> '.$author.'</b></span><br>
            <p style="overflow-wrap: break-word; white-space: pre-wrap;">'.$text.'</p>
            <img src="uploads/'.$fileName.'">
        </body>
        </html>
        ';
                }else{echo "Your file is too big!";}
            }else{echo "There was an error while uploading your file!";}
        }else{
        if($fileError === 0){
        	if($fileSize < 100000000){
        		$fileDestination = 'post/uploads/'.$fileName;
        		move_uploaded_file($fileTmpName, $fileDestination);
        $content = '
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>'.$title.'</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
        </head>
        <body style="font-family: ubuntu;">
            <span>author:<b> '.$author.'</b></span><br>
            <p style="overflow-wrap: break-word; white-space: pre-wrap;">'.$text.'</p>
            <a href="uploads/'.$fileName.'">'.$fileName.'</a>
        </body>
        </html>
        ';
        	}else{echo "Your file is too big!";}
		}else{
		$content = '
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>'.$title.'</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
        </head>
        <body style="font-family: ubuntu;">
            <span>author:<b> '.$author.'</b></span><br>
            <p style="overflow-wrap: break-word; white-space: pre-wrap;">'.$text.'</p>
            <a href="uploads/'.$fileName.'">'.$fileName.'</a>
        </body>
        </html>
        ';
		}
        }
    }

    if (!file_exists("post/".$title."-".$author.".php") && !empty($author && $title)){
        $fp = fopen("post/".$title."-".$author.".php","wb");
        fwrite($fp,$content);
        fclose($fp);

        $fl = fopen("list.php","a");
        fwrite($fl,"<a href='post/".$title."-".$author.".php'>".$title."-".$author."</a><br>");
        fclose($fl);

    } else {
        echo "<script>alert('file name exist or name or title empty');</script>";
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Upload</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
</head>
<body style="font-family: ubuntu">
    <h1>Create posts</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label>author</label><br>
        <input type="text" name="authorinput"/><br>
        <label>title</label><br>
        <input type="text" name="titleinput"/><br>
        <label>text (can enlarge)</label><br>
        <textarea id=textbox name="textinput"/></textarea><br>
        <label>file (optional)</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload" size="35"><br><br>
        <input type="submit" name="SubmitButton"/><br><br>
    </form>
<h1 style="display: inline-block;">Posts&nbsp;&nbsp;</h1><a href="/upload">reload</a><br>
<?php include("list.php"); ?>
</body>
<script type="text/javascript">
document.getElementById('textbox').addEventListener('keydown', function(e) {
  if (e.key == 'Tab') {
    e.preventDefault();\
    var start = this.selectionStart;
    var end = this.selectionEnd;

    this.value = this.value.substring(0, start) +
      "\t" + this.value.substring(end);

    this.selectionStart =
      this.selectionEnd = start + 1;
  }
});
</script>
</html>