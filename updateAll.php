<?php
include "createPiecePage.php";
include "editClassPage.php";
include "editTeacherPage.php";
include "editHomepage.php";

$connection = mysqli_connect( "localhost" , "ramzi" , "19632963" , "ramzi_artshow" );

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

echo    "All pages updated!";
?>