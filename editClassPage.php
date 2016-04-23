<?php
function editClassPage( $connection , $id , $teacherID , $fileName )
{
    $classQuery = mysqli_query( $connection , "SELECT * FROM classes WHERE id = $id AND teacher = $teacherID" );
    if( $class = mysqli_fetch_array( $classQuery ) )
    {
        $className = $class[ "name" ];
        $teacher = $class[ "teacher" ];
        $content = "<!DOCTYPE HTML>
<html>
    <head>
        <title>$className | STHS Art Show 2016</title>
        <meta charset = 'utf-8'/>
        <link rel = 'stylesheet' type = 'text/css' href = '../style.css'/>
        <link rel = 'icon' type = 'image/png' href = '../images/favicon.png'>
    </head>
    <body>
        <div id = 'header'>
            <div id = 'headerTitle'>
                <a href = '../index.html'>STHS ART SHOW 2016</a>
            </div>
            <div id = 'headerBar'></div>
        </div>

        <div id = 'gallerySection'>
            <h1>$className</h1>
            <center>";
        $piecesQuery = mysqli_query( $connection , "SELECT * FROM pieces WHERE class = $id AND teacher = $teacherID ORDER BY last ASC , id ASC, data ASC" );
        $takenIDs = array();
        while( $piece = mysqli_fetch_array( $piecesQuery ) )
        {
            $pieceID = $piece[ "id" ];
            $pieceType = $piece[ "type" ];
            $addPiece = true;
            foreach( $takenIDs as $currentTakenID )
            {
                if( $currentTakenID == $pieceID )
                {
                    $addPiece = false;
                }
            }
            
            if( $addPiece )
            {
            	array_push( $takenIDs , $pieceID );
            	$data = $piece[ "data" ];
            	$lastName = $piece[ "last" ];
            	$url = $piece[ "url" ];
            	if( $pieceType == 1 )
            	{
            		$thumbnailDirectory = "../pieces/thumbnails/$data";
            	}
            	else if( $pieceType == 2 )
        	{
        		$thumbnailDirectory = "../images/music.png";
        	}
        	else if( $pieceType == 3 )
        	{
        		$thumbnailDirectory = "../video-thumbnails/$data";
        	}
            	
            	$content = $content . "<div class = 'galleryPiece'>
                    <div class = 'padded'>
                        <a href = '$url'>
                            <img src = '$thumbnailDirectory'/>
                            <h3>$lastName</h3>
                        </a>
                    </div>
                </div>";
            }
        }
        $content = $content . "</center>
                </div>
            </body>
        </html>";

        $newFile = fopen( "pages/" . $fileName , "w" );
        fwrite( $newFile , $content );
        fclose( $newFile );
        
        echo $fileName . " " . strlen( $content ) . "<br/>";
    }
}
?>