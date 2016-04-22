function initialize()
{
    $( "#artType" ).change( typeChange );
    $( "#artType" ).val( 0 );
    $( "#image" ).change( valueChange );
    $( "#music" ).change( valueChange );
    $( "#teacher" ).change( teacherChange );
    $( "#teacher" ).val( 0 );
    $( ".classSelector" ).change( valueChange );
    $( ".classSelector" ).val( 0 );
    $( "#first").on( "input" , valueChange );
    $( "#last" ).on( "input" , valueChange );
    $( "#title" ).on( "input" , valueChange );
    $( "#video" ).on( "input" , valueChange );
    $( "#button" ).click( submit );

    window.idSet = false;
    window.multipleImages = false;
}

function typeChange()
{
    var value = this.value;
    if( value == 0 )
    {
        $( "#image" ).css( "display" , "none" );
        $( "#music" ).css( "display" , "none" );
        $( "#video" ).css( "display" , "none" );
        $( "#videoThumbnail" ).css( "display" , "none" );
    }
    else if( value == 1 )
    {
        $( "#image" ).css( "display" , "block" );
        $( "#music" ).css( "display" , "none" );
        $( "#video" ).css( "display" , "none" );
        $( "#videoThumbnail" ).css( "display" , "none" );
    }
    else if( value == 2 )
    {
        $( "#image" ).css( "display" , "none" );
        $( "#music" ).css( "display" , "block" );
        $( "#video" ).css( "display" , "none" );
        $( "#videoThumbnail" ).css( "display" , "none" );
    }
    else if( value == 3 )
    {
        $( "#image" ).css( "display" , "none" );
        $( "#music" ).css( "display" , "none" );
        $( "#video" ).css( "display" , "block" );
        $( "#videoThumbnail" ).css( "display" , "block" );
    }

    valueChange();
}

function teacherChange()
{
    var teacher = this.value;
    if( teacher == 0 )
    {
        $( "#classSpan" ).css( "display" , "none" );
    }
    else
    {
        $( "#classSpan" ).css( "display" , "inline" );
    }

    var i = 0;
    $( ".classSelector" ).each( function(){
        i++;
        if( i == teacher )
        {
            $( this ).css( "display" , "inline" );
            this.name = "class";
        }
        else
        {
            $( this ).css( "display" , "none" );
            this.name = "";
        }
    } );

    valueChange();
}

function valueChange()
{
    var enabled = true;
    var type = $( "#artType" ).val();
    if( type == 0 )
    {
        enabled = false;
    }
    else if( type == 1 )
    {
        if( !$( "#image" ).val() )
        {
            enabled = false;
        }
    }
    else if( type == 2 )
    {
        if( !$( "#music" ).val() )
        {
            enabled = false;
        }
    }
    else if( type == 3 )
    {
        if( !$( "#video" ).val() )
        {
            enabled = false;
        }
    }

    var teacher = $( "#teacher" ).val();
    if( teacher == 0 )
    {
        enabled = false;
    }
    else
    {
        if( $( ".classSelector" )[ teacher - 1 ].value == 0 )
        {
            enabled = false;
        }
    }

    if( !$( "#first" ).val() || !$( "#last" ).val() || !$( "#title" ).val() )
    {
        enabled = false;
    }


    if( enabled )
    {
        $( "#button" ).addClass( "enabled" );
    }
    else
    {
        $( "#button" ).removeClass();
    }
}

function submit()
{
    if( this.className == "enabled" )
    {
        var value = $( "#artType" ).val();
        if( value == 1 )
        {
            window.images = $( "#image" )[ 0 ].files;
            var first = window.images[ 0 ];
            var length = window.images.length;
            if( length > 1 )
            {
                window.multipleImages = true;
                window.imagesToSubmit = length;
                window.imagesSubmitted = 0;
                submitForm( first , null , false );
            }
            else
            {
                submitForm( first , null , true );
            }
        }
        else if( value == 2 )
        {
            submitForm( $( "#music" )[ 0 ].files[ 0 ] , null , true );
        }
        else if( value == 3 )
        {
            submitForm( $( "#videoThumbnail input" )[ 0 ].files[ 0 ] , null , true );
        }
    }
    else
    {
        $( "#warning" ).css( "display" , "block" );
    }
}

function submissionCompleted( data )
{
    if( window.multipleImages )
    {
        if( !window.idSet )
        {
            window.idSet = true;
            window.id = data;
        }
        window.imagesSubmitted++;
        if( window.imagesSubmitted < window.imagesToSubmit )
        {
            var nextImage = window.images[ window.imagesSubmitted ];
            if( window.imagesSubmitted == window.imagesToSubmit - 1 )
            {
                submitForm( nextImage , window.id , true );
            }
            else
            {
                submitForm( nextImage , window.id , false );
            }
        }
        else
        {
            finish();
        }
    }
    else
    {
        finish();
    }
}

function finish()
{
    $( "body" ).html( "<div id = 'thank'>Thank you!</div>" );
}

function submitForm( file , id , createPage )
{
    var form = $( "#mainForm" )[ 0 ];
    var formData = new FormData( form );
    formData.append( "createPage" , createPage );
    if( file != null )
    {
        formData.append( "file" , file );
    }
    if( id != null )
    {
        formData.append( "id" , window.id );
    }
    
    $( "#progress" ).css( "display" , "block" );
    $.ajax(
        {
            xhr: function()
            {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener( "progress" , function( evt )
                {
                    if ( evt.lengthComputable )
                    {
                        var percentComplete = ( evt.loaded / evt.total ) * 100;
                        if( window.multipleImages )
                        {
	                	percentComplete = ( ( window.imagesSubmitted / window.imagesToSubmit ) * 100 ) + ( percentComplete / window.imagesToSubmit );
                        }
                        if( percentComplete > 99 )
                        {
                        	percentComplete = 99;
                        }
                        percentComplete = percentComplete.toFixed( 1 ) + "%";
                        $( "#progressBar" ).css( "width" , percentComplete );
                        $( "#progressPercentage" ).html( percentComplete );
                    }
                }, false );

                return xhr;
            },

            type: 'POST',
            url: form.action ,
            data: formData ,
            cache: false ,
            contentType: false ,
            processData: false ,
            success: function(data){submissionCompleted( data );console.log(data)},
            error: function(data){ console.log( "Form submission failed" ) }
        });
}

window.onload = initialize;