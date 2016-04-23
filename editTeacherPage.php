<?php
function editTeacherPage( $connection , $teacherID )
{
    $teacherQuery = mysqli_query( $connection , "SELECT * FROM teachers WHERE id = $teacherID" );
    if( $teacher = mysqli_fetch_array( $teacherQuery ) )
    {
        $teacherName = $teacher[ "first" ] . " " . $teacher[ "last" ];
        $sliders = "";
        $classesQuery = mysqli_query( $connection , "SELECT * FROM classes WHERE teacher = $teacherID ORDER BY id ASC" );
        while( $class  = mysqli_fetch_array( $classesQuery ) )
        {
            $classID = $class[ "id" ];
            $classUrl = $class[ "url" ];
            $name = $class[ "name" ];
            $sliders = $sliders . "<h1><a href = '$classUrl'>$name</a></h1>";
            $sliders = $sliders . "<div class = 'slider'>
                <div class = 'arrow'></div>
                <div class = 'center'>
                    <div class = 'centerWrapper'>";
            $sliders = $sliders . "<div class = 'centerContainer' style = 'left: 0'>";
            $piecesQuery = mysqli_query( $connection , "SELECT * FROM pieces WHERE teacher = $teacherID AND class = $classID ORDER BY last ASC , id ASC , data ASC" );
            $takenIDs = array();
            $i = 0;
            while( ( $i < 7 ) && $piece = mysqli_fetch_array( $piecesQuery ) )
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
                
                if( $pieceType == 2 )
                {
                	$addPiece = false;
                }

                if( $addPiece )
                {
                    array_push( $takenIDs , $pieceID );
                    $data = $piece[ "data" ];
                    $url = $piece[ "url" ];
                    if( $pieceType == 1 )
                    {
                	$thumbnailDirectory = "pieces/thumbnails/$data";
                    }
                    else
                    {
                	$thumbnailDirectory = "video-thumbnails/$data";
                    }
                    $sliders = $sliders . "<div class = 'slide'>
                            <div><a href = '$url'><img src = '../$thumbnailDirectory'/></a></div>
                        </div>";
                    $i++;

                    if( $i == 4 )
                    {
                        $sliders = $sliders . "</div>
                        <div class = 'centerContainer' style = 'left: 100%'>";
                    }
                }
            }
            $sliders = $sliders . "<div class = 'slide'>
                            <div><a href = '$classUrl'><img src = '../images/all.png'/></a></div>
                        </div>";
            $sliders = $sliders . "</div>";
            $sliders = $sliders . "</div>
                </div>
                <div class = 'arrow'></div>
            </div>";
        }
        $pageContent = "
<!DOCTYPE HTML>
<html>
    <head>
        <title>HUMAN | STHS Art Show 2016</title>
        <meta charset = 'utf-8'/>
        <script src = '../javascript/jquery.js'></script>
        <script src = '../javascript/main.js'></script>
        <link rel = 'stylesheet' type = 'text/css' href = '../style.css'/>
        <link rel = 'icon' type = 'image/png' href = '../images/favicon.png'>
    </head>

    <body>
        <div id = 'header'>
            <div id = 'headerTitle'>
                <a href = '../index.html'/>STHS ART SHOW 2016</a>
            </div>
            <div id = 'headerBar'></div>
        </div>
        <div id = 'bodyWrapping'>
            $sliders
        </div>
    </body>
</html>";

        $fileName = "pages/" . $teacherName . ".html";
        $fileName = str_replace( " " , "_" , $fileName );
        $fileName = str_replace( "'" , "" , $fileName );
        $fileName = strtolower( $fileName );

        $newFile = fopen( $fileName , "w" );
        fwrite( $newFile, $pageContent );
        fclose( $newFile );
    }
}
?>