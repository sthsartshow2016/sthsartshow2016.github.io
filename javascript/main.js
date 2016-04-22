function initialize()
{
    window.onresize = resize;
    resize();
    $( ".slider" ).each( initializeSlider );
}

function initializeSlider()
{
    this.currentSlide = 4;
    this.numberOfSlides = $( ".slide" , this ).length;
    $( ".arrow" , this )[ 0 ].onclick = moveSliderLeft;
    $( ".arrow" , this )[ 1 ].onclick = moveSliderRight;
}

function moveSliderLeft()
{
    moveSlider( this , -1 );
}

function moveSliderRight()
{
    moveSlider( this , 1 );
}

function moveSlider( arrow , direction )
{
    var slider = arrow.parentNode;
    var numberOfSlides = slider.numberOfSlides;
    var currentSlide = slider.currentSlide;
    var newSlide = currentSlide + ( 4 * direction );
    if( newSlide < 4 )
    {
        newSlide = 4;
    }
    else if( newSlide > numberOfSlides )
    {
        newSlide = numberOfSlides;
    }

    var i = 0;
    $( ".centerContainer" , slider ).each( function(){
        var newPosition = 25 * ( ( 4 * i ) + 4 - newSlide );
        this.style.left = newPosition + "%";
        i++;
    });
    slider.currentSlide = newSlide;
}

function resize()
{
    $( ".slider" ).each( resizeSlider );
}

function resizeSlider()
{
    var height = $( ".centerContainer" , this ).height() + "px";
    $( ".center" , this ).css( "height" , height );
}

window.onload = initialize;