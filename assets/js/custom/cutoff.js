$(document).ready(function(){
    var cutoffid = $('#cutoffid').val()
    getTable(cutoffid)

    function getTable(cutoffid){
        $.ajax({
            url: baseUrl + 'review/cutoff/detailTable',
            type: 'get',
            data: {cutoffid : cutoffid},
            beforeSend: function(){
                $('.table-summary').empty()
            },
            success: function(res){
                $('.table-summary').html(res)
            }
        })
    }
})