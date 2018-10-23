<html>
<head>
<title>db_import</title>
</head>

<form method="POST" style="text-align:center;padding:10px;" enctype="multipart/form-data">
<input type='file' name='file' id='c_file' required>
{{ csrf_field() }}
<input type="submit">
</form>




@if($data['upload'])
<?php 
print_r($data['file_content']);
 ?>
@else
<span>not upload</span>
@endif

</html>


