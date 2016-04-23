<?php
$connection = mysqli_connect( "localhost" , "ramzi" , "19632963" , "ramzi_artshow" );

$firstName = removeQuotes( $_POST[ "firstName" ] );
$lastName = removeQuotes( $_POST[ "lastName" ] );
$pieceTitle = removeQuotes( $_POST[ "pieceTitle" ] );
$statement = removeQuotes( $_POST[ "statement" ] );
$type = $_POST[ "type" ];
$teacher = $_POST[ "teacher" ];
$class = $_POST[ "class" ];
$id = $_POST[ "id" ];
$createPage = $_POST[ "createPage" ];

if( !isset( $id ) )
{
    $id = 0;
    while( true )
    {
        $IDMatchesQuery = mysqli_query( $connection , "SELECT * FROM pieces WHERE id = $id" );
        if( mysqli_fetch_array( $IDMatchesQuery ) )
        {
            $id++;
        }
        else
        {
            break;
        }
    }
}
echo     $id;

$data;

if( $type == 1 )
{
	$directoryFolder = "pieces";
}
else if( $type == 2 )
{
	$directoryFolder = "music";
}
else if( $type == 3 )
{
	$directoryFolder = "video-thumbnails";
}

$file = $_FILES[ "file" ];
$tempDirectory = $file[ "tmp_name" ];//Temporary director
$fileName = $file[ "name" ];//Uploaded file's name
$fileName = calculateFileName( $directoryFolder , $fileName );
$newDirectory = $directoryFolder . "/" . $fileName;
move_uploaded_file( $tempDirectory , $newDirectory );//Moves temp file to new directory

if( $type == 1 || $type == 3 )
{
	if( $type == 1 )
	{
		$thumbnailDirectory = "pieces/thumbnails/" . $fileName;
		copy( $newDirectory , $thumbnailDirectory );
		
		$originalImage = new Imagick( $_SERVER['DOCUMENT_ROOT'] . "/art/" . $newDirectory );
		$height = $originalImage->getImageHeight();
		$width = $originalImage->getImageWidth();
		if( $width > $height )
		{
			if( $width > 1920 )
			{
				$originalImage->scaleimage( 1920 , 0 );
			}
		}
		else
		{
			if( $height > 1920 )
			{
				$originalImage->scaleimage( 0 , 1920 );
			}
		}
		$originalImage->writeImage();
	}
	else
	{
		$thumbnailDirectory = $newDirectory;
	}

	$image = new Imagick( $_SERVER['DOCUMENT_ROOT'] . "/art/" . $thumbnailDirectory );
	$image->cropThumbnailImage( 450 , 300 );
	$image->writeImage();
}
    
$data = $fileName;

if( $type == 3 )
{
    $videoEmbed = $_POST[ "video" ];
}

$query = "INSERT INTO pieces ( id , first , last , title , statement , data , videoEmbed , teacher , type , class ) 
VALUES ( $id , '$firstName' , '$lastName' , '$pieceTitle' , '$statement' , '$data' , '$videoEmbed' , $teacher , $type , $class )";
mysqli_query( $connection , $query );

if( $createPage == "true" )
{
    include "createPiecePage.php";
    createPiecePage( $connection , $id );
    
include "editClassPage.php";
include "editTeacherPage.php";
include "editHomepage.php";

$classesQuery = mysqli_query( $connection , "SELECT * FROM classes" );
while( $class = mysqli_fetch_array( $classesQuery ) )
{
    $classID = $class[ "id" ];
    $teacherID = $class[ "teacher" ];
    $url = $class[ "url" ];
    editClassPage( $connection , $classID , $teacherID , $url );
}

$teachersQuery = mysqli_query( $connection , "SELECT * FROM teachers" );
while( $teacher = mysqli_fetch_array( $teachersQuery ) )
{
    $teacherID = $teacher[ "id" ];
    editTeacherPage( $connection , $teacherID );
}

editHomepage( $connection );
}

function calculateFileName( $directoryFolder , $fileName )
{
    $fileName = str_replace( " " , "" , $fileName );//Removes all spaces from file name
    /*
    * If another file already has the new file's name, add an appropriate integer to the beginning of the new file's name
    * Otherwise return the original file name
    */
    if( file_exists( "$directoryFolder/$fileName" ) )
    {
        $i = 0;
        while( true )
        {
            if( file_exists( "$directoryFolder/$i$fileName" ) )
            {
                $i++;
            }
            else
            {
                return $i . $fileName;
            }
        }
    }
    else
    {
        return $fileName;
    }
}

function removeQuotes( $string )
{
    return str_replace( "'" , "''" , $string );
}
?>