$(document).ready(function(){
    $('.btn-search').click(function(){
        var startDate = $('#startDate').val()
        var endDate = $('#endDate').val()
        var cabangid = $('#cabang_id').val()
    
        $.ajax({
            url : baseUrl + 'trx/summary/searchTable',
            type: 'get',
            data: {startDate : startDate, endDate : endDate, cabangid : cabangid},
            beforeSend: function(){
                $('.table-summary').empty()
            },
            success: function(res){
                $('.table-summary').html(res)
            }
        })
    })
})