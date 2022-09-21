$(document).ready(function(){
    var cutoffid = $('#cutoffid').val()
    var logid = $('#logid').val()

    getTable(cutoffid, logid)

    function getTable(cutoffid){
        $.ajax({
            url: baseUrl + 'mitra/detailTable',
            type: 'get',
            data: {cutoffid : cutoffid, logid : logid},
            beforeSend: function(){
                $('.table-summary').empty()
            },
            success: function(res){
                $('.table-summary').html(res)
            }
        })
    }
})