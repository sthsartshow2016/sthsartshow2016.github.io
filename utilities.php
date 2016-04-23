<?php
function calculateFileName( $directoryFolder , $fileName )
{
    $fileName = str_replace( " " , "" , $fileName );//Removes all spaces from file name
    /*
    * If another file already has the new file's name, add an appropriate integer to the beginning of the new file's name
    * Otherwise return the original file name
    */
    if( file_exists( "../../$directoryFolder/$fileName" ) )
    {
        $i = 0;
        while( true )
        {
            if( file_exists( "../../$directoryFolder/$i$fileName" ) )
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

//Calculates the lowest unused post ID in a given table
function calculatePostID( $connection , $tableName )
{
    $postID = 0;
    while( true )
    {
        if( valueExists( $connection , $tableName , "id" , $postID ) )
        {
            $postID++;
        }
        else
        {
            return $postID;
        }
    }
}

function generateAlbumsSelector( $connection , $tableName )
{
    echo    "Album: <br/>";
    $recordedAlbums = array();//Keeps track of which albums have already been printed

    echo    "<select id = 'albumSelector' name = 'album'>";
    echo    "<option value = ''>None</option>";
    $postsQuery = mysqli_query( $connection , "SELECT * FROM $tableName" );
    //Echos a single option element for each album
    while( $post = mysqli_fetch_array( $postsQuery ) )
    {
        $currentAlbum = $post[ "album" ];
        if( !empty( $currentAlbum ) )
        {
            //If album is not in array, adds it and echos option element
            $albumAlreadyRecorded = false;
            foreach( $recordedAlbums as $recordedAlbum )
            {
                if( $recordedAlbum == $currentAlbum )
                {
                    $albumAlreadyRecorded = true;
                }
            }
            if( !$albumAlreadyRecorded )
            {
                array_push( $recordedAlbums , $currentAlbum );
                echo    "<option value = '$currentAlbum'>$currentAlbum</option>";
            }
        }
    }
    echo    "<option id = 'customAlbumOption'>Custom...</option>";
    echo    "</select>";
}

//Removes spaces and makes all letters lowercase
function simplify( $text )
{
    return str_replace( " " , "" , strtolower( $text ) );
}

//Checks if a given value exists in a table
function valueExists( $connection , $tableName , $columnName , $value )
{
    return mysqli_fetch_array( mysqli_query( $connection , "SELECT * FROM $tableName WHERE $columnName = '$value'" ) );
}
?>