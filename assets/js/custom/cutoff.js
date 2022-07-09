$(document).ready(function(){
    var cabangid = localStorage.getItem('cabangid')
    var cutoffid = $('#cutoffid').val()
    
    if(cabangid){
        getTable(cabangid)
        $("#cabang_id").val(cabangid)
    }

    $('#cabang_id').change(function(){
        var cabangid = $(this).val()
        getTable(cabangid)
    })

    function getTable(cabangid){
        $.ajax({
            url: baseUrl + 'review/cutoff/detailTable',
            type: 'get',
            data: {cabangid : cabangid, cutoffid : cutoffid},
            beforeSend: function(){
                $('.table-summary').empty()
            },
            success: function(res){
                localStorage.setItem('cabangid', cabangid)
                $('.table-summary').html(res)
            }
        })
    }
})