$('#select-div').change(function(){
    var id = $(this).val()
    $.ajax({
        url: nowUrl + '/get_dept',
        type: 'get',
        data: {id : id},
        success: function(res){
            $('#select-dept')
                .prop("disabled", false)
                .find("option")
                .remove()
                .end()
                .append("<option value='' selected disabled>- Pilih Departement </option>")
            for (var i = 0; i < res.length; i++){
                var dept = res[i].departement
                var dept_id = res[i].id
                $('#select-dept').append("<option value='" + dept_id + "'>" + dept + "</option>")
            }
        }
    })
})