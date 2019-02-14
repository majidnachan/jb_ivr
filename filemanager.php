<?php
//echo "<pre/>";

// Root path for file manager
//var_dump($_GET);
//var_dump($_POST);
//var_dump($_REQUEST);
if(isset($_REQUEST['path']) && !empty($_REQUEST['path'])){
	$rootPath = urldecode($_REQUEST['path']);
}else{
	$rootPath = $_SERVER['DOCUMENT_ROOT'];	
}
//var_dump($rootPath);
//die;

function getBackPath($path){
	$backPath = dirname($path);
	$backPath = str_replace("\\","/",$backPath);
	return $backPath;
}

function getFileExtnsn($file){
	$info = new SplFileInfo($file);
	return $info->getExtension();
}

function listDirectory($dir){
	$files = array();
	$folder = array();
	if(is_dir($dir)){
		$dirData = scandir($dir);	
	}else{
		$dirData = array();
	}
	

	if(!empty($dirData)){
		foreach ($dirData as $key => $value) {
			if(substr($dir, -1) == '/' ){
				$path = $dir . $value;	
			}else{
				$path = $dir .'/'. $value;	
			}
			
			if(is_dir($path)){
				if($value != "."){
					$folder[] = $value;	
				}
			}else{
				$extn = getFileExtnsn($value);
				//$mimetype = mime_content_type($value);
				$isText = 0;
				/*if($mimetype == 'text/plain'){
					$isText = 1;
				}*/
				$files[] = array('file'=>$value,'extension'=>$extn,'isText'=>$isText);
			}
		}
		return json_encode(array('files'=>$files,'folder'=>$folder));
	}else{
		return json_encode(array());
	}
}
//var_dump(listDirectory($rootPath));
//die; 

?>
<!DOCTYPE html>
<html>
    <head>
        <title>File Manager</title>
        <meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
	  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	  	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	  	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>-->
        <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
		<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->
    </head>
    <body>
    	<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    		<div class="container">
      			<a class="navbar-brand" href="#">File Manager</a>
      			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        			<span class="navbar-toggler-icon"></span>
      			</button>
      		</div>
  		</nav>
  		<div class="container">
  			<div class="jumbotron">
  				<div class="row">
  					<div class="col-md-10">
  						<b id="path"></b>	
  					</div>
  					<div class="col-md-2">
  						<button type="button" class="btn btn-outline-dark" id="back">Back</button>
  					</div>
				</div>
				<div class="row">
					<label for="customPath">Custom Path:</label>
			  		<div class="input-group mb-3">
							<input type="text" class="form-control" placeholder="Enter Path" id="customPath">
							<div class="input-group-append">
								<button class="btn btn-success" id="customPathSelect">Go</button> 
							</div>
					</div>
				</div>
  			</div>
  		</div>
  		<div class="container">
			<!--<div class="row">
		  		<div class="input-group mb-3 col-md-6">
						<input type="text" class="form-control" placeholder="Search" id="searchDir">
						<div class="input-group-append">
							<button class="btn btn-success" id="searchDirList"><i class="material-icons">arrow_forward</i></button> 
						</div>
				</div>
			</div>-->
  		</div>
		<div class="container">
			<table class="table table-hover" id="listTable">
			    <thead>
		      		<tr>
				        <th>Name</th>
				        <th>Extension</th>
				        <th>Type</th>
				        <th>Actions</th>
			      	</tr>
			    </thead>
			    <tbody>
			    </tbody>
		  	</table>
		</div>
		<div>
			<form id="postPathValue" method="post">
				<input type="hidden" name="path" value="" id="formPathValue"/>
			</form>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				//console.log('ready');
				//return false;

				var rootPath = "<?php echo $rootPath; ?>";
				//console.log(rootPath);
				$('#path').html(rootPath);
				
				var directoryData = <?php echo listDirectory($rootPath); ?>;
				//console.log(typeof(directoryData));
				if(typeof(directoryData) == 'object' && Object.keys(directoryData).length > 0){
					$.each(directoryData, function( index, value ) {
						$.each(value, function( key, val ) {
							if(index == 'files'){
								var markup = '<tr>';
								if(val['isText']){
									markup += '<td class="fileName"><a href class="viewContent">'+val['file']+'</a></td>';
								}else{
									markup += '<td class="fileName">'+val['file']+'</td>';
									
								}
								markup += '<td>'+val['extension']+'</td><td>File</td><td><button type="button" class="btn btn-outline-dark copyPath">Copy Path</button></td></tr>';
								
							}else{
								var markup = '<tr><td><a href class="viewSubDir">'+val+'</a></td><td></td><td>File Folder</td><td></td></tr>';
							}
							//console.log(markup);
							$('table tbody').append(markup);
						});
					});
				}else{
					var markup = '<tr><td>No data found</td><td></td></tr>';
					$('table tbody').append(markup);
				}

				/*On directory back click*/
				$(document).on('click','#back',function(){
					var backPath = "<?php echo getBackPath($rootPath); ?>";
					backPath = encodeURIComponent(backPath);
					//var url = window.location.href;
				   	//url = '?path=' + backPath;

				   	$('#formPathValue').val(backPath);
				   	$('#postPathValue').submit();
					
					//console.log("--->"+url);
					//console.log("--->"+window.location.href);
					//window.location.href = url;
				});

				/*On custom path search*/
				$(document).on('click','#customPathSelect',function(){
					var customPath = $('#customPath').val();
					customPath = encodeURIComponent(customPath.replace('\\','/'));
					$('#formPathValue').val(customPath);
				   	$('#postPathValue').submit();
				   	//var url = window.location.href;
				   	//url = '?path=' + customPath;
					
					//console.log("--->"+url);
					//console.log("--->"+window.location.href);
					//window.location.href = url;
				});

				/*On View sub folder*/
				$(document).on('click','.viewSubDir',function(event){
					event.preventDefault();
					var folderPath = "<?php echo $rootPath; ?>";
					var currDir = $(this).html();
					if(folderPath[folderPath.length-1] == '/'){
						folderPath = encodeURIComponent(folderPath +  currDir);	
					}else{
						folderPath = encodeURIComponent(folderPath + '/' + currDir);
					}
					$('#formPathValue').val(folderPath);
				   	$('#postPathValue').submit();

					//var url = window.location.href;
				   	//url = '?path=' + folderPath;
					
					//console.log("--->"+url);
					//console.log("--->"+window.location.href);
					//window.location.href = url;

				});

				/*On copy path*/
				$(document).on('click','.copyPath',function(event){
					var folderPath = "<?php echo $rootPath; ?>";
					var fileName = $(this).parent().parent().find('.fileName').html();
					var filePath = '';
					//console.log(fileName);
					
					if(folderPath[folderPath.length-1] == '/'){
						filePath = folderPath +  fileName;
					}else{
						filePath = folderPath + '/' + fileName;
					}
					var $temp = $("<input>");
				  	$("body").append($temp);
				  	$temp.val(filePath).select();
			 	 	document.execCommand("copy");
				  	$temp.remove();

				});
				
			});
		</script>
    </body>
</html>