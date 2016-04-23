<?php
$connection = mysqli_connect( "localhost" , "ramzi" , "19632963" , "ramzi_artshow" );
?>

<html>
    <head>
        <link rel = "stylesheet" type = "text/css" href = "edit.css"/>
        <link rel = "icon" type = "image/png" href = "images/favicon.png">
        <script src = "javascript/jquery.js"></script>
        <script src = "javascript/edit.js"></script>
        <title>Upload</title>
    </head>

    <body>
        <div id = "progress">
            <div id = "progressBar"></div>
            <span id = "progressPercentage"></span>
        </div>
        <i>* = Required field</i>
        <form id = "mainForm" action = "addPiece.php" method = "post">
            <input type = "text" placeholder = "*First Name" id = "first" name = "firstName"/>
            <input type = "text" placeholder = "*Last Name" id = "last" name = "lastName"/>
            <input type = "text" placeholder = "*Piece(s) Title" id = "title" name = "pieceTitle"/>
            <textarea placeholder = "Artist's Statement" name = "statement"></textarea>
            <div id = "teacherClassSeclection" style = "margin-bottom: 10px">
                *Teacher:
                <select id = "teacher" name = "teacher">
                    <option value = "0">- Select -</option>
                    <?php
                    $teachersQuery = mysqli_query( $connection , "SELECT * FROM teachers ORDER BY id ASC" );
                    while( $teacher = mysqli_fetch_array( $teachersQuery ) )
                    {
                        $id = $teacher[ "id" ];
                        $lastName = $teacher[ "last" ];
                        echo    "<option value = '$id'>$lastName</option>";
                    }
                    ?>
                </select>

                <span id = "classSpan">*Class:</span>
                <?php
                $teachersQuery = mysqli_query( $connection , "SELECT * FROM teachers ORDER BY id ASC" );
                while( $teacher = mysqli_fetch_array( $teachersQuery ) )
                {
                    $id = $teacher[ "id" ];
                    echo    "<select class = 'classSelector'>";
                    echo        "<option value = '0'>- Select -</option>";
                    $classesQuery = mysqli_query( $connection , "SELECT * FROM classes WHERE teacher = $id ORDER BY id ASC" );
                    while( $class = mysqli_fetch_array( $classesQuery ) )
                    {
                        $classID = $class[ "id" ];
                        $className = $class[ "name" ];
                        echo    "<option value = '$classID'>$className</option>";
                    }
                    echo    "</select>";
                }
                ?>
            </div>
            <div style = "margin-bottom: 10px">
                *Type:
                <select id = "artType" name = "type">
                    <option value = "0">- Select -</option>
                    <option value = "1">Images</option>
                    <option value = "2">Music</option>
                    <option value = "3">Video</option>
                </select>
            </div>
            
            <input type = "text" placeholder = "Video Embed Link" id = "video" name = "video"/>
            <div id = "videoThumbnail">
        	*Thumbnail: 
        	<input type = "file" accept = "image/*" name = "videoThumbnail"/>
            </div>
        </form>
        <input type = "file" accept = "image/*" id = "image" multiple/>
        <input type = "file" id = "music" accept = ".mp3"/>
        <div id = "buttonContainer">
            <center>
                <span id = "warning">Please complete all required fields</span>
                <div id = "button">Upload</div>
            </center>
        </div>
    </body>
</html>