
$('#iconNavbarSidenav2').click(function(){
    if($('body').hasClass('g-sidenav-pinned')){
        $('#sidenav-main').addClass('fixed-start').removeClass('d-none')
        $('body').removeClass('g-sidenav-pinned')
    }else{
        $('#sidenav-main').addClass('d-none').removeClass('fixed-start')
        $('body').addClass('g-sidenav-pinned')
    }
})