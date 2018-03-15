<?php
echo $_REQUEST['input4'];
?>


<script type="text/javascript" src="js/jszip.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script> 
<script>
if (window.File && window.FileReader && window.FileList && window.Blob) {
  // Great success! All the File APIs are supported.
} else {
  alert('The File APIs are not fully supported in this browser.');
}
</script>

<form method='POST'>
<input type="file" id="files" name="files[]" multiple >
<output id="list"></output>
<hr>
<input type="file" class="upload-input" name="files2[]" >
<hr>
<input type="file" id="input3" >
<hr>
<input type="file" id="input4" name='input4' multiple='multiple' >
<input type='submit'>
</form>

<script>
  function handleFileSelect(evt) {
    var files = evt.target.files;
    var output = [];
    for (var i = 0, f; f = files[i]; i++) {
      output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                  f.size, ' bytes, last modified: ',
                  f.lastModifiedDate.toLocaleDateString(), '</li>');
    }
    document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
  }
  document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>

<script>
var zip = new JSZip();
$('.upload-input').on('change', function(event) {
 var file = event.target.files[0];
 console.log(file);
 JSZip.loadAsync(file).then(function(content) {
  return content.files["test.txt"].async('text');
 }).then(function (txt) {
   alert(txt);
 });
});
$('#input3').on('change',function(event){
	var file = event.target.files[0];
	zip.file('f.txt',file);
	zip.file('f.txt').async('text').then(function(txt){console.log(txt);});
});
$('#input4').on('change',function(event){
	var files = event.target.files;
	for(var i=0;i<files.length;i++)
	{
		zip.file(files[i].name,files[i]);
	}
	zip.generateAsync({type:'blob'}).then(function (content) {
		var zipfile = new File([content], "name");
		files[0] = zipfile;
	});
});
</script>