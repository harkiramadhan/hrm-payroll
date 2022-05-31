$('#select-div').change(function(){
    var id = $(this).val()
    $.ajax({
        url: siteUrl + 'master/employee/get_dept',
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
                var dept_id = res[i].id + '_' + dept
                $('#select-dept').append("<option value='" + dept_id + "'>" + dept + "</option>")
            }
        }
    })
})

$('#select-dept').change(function(){
    var id = $(this).val()
    $.ajax({
        url: siteUrl + 'master/employee/get_unit',
        type: 'get',
        data: {id : id},
        success: function(res){
            $('#select-unit')
                .prop("disabled", false)
                .find("option")
                .remove()
                .end()
                .append("<option value='' selected disabled>- Pilih Unit </option>")

            for (var i = 0; i < res.length; i++){
                var unit = res[i].unit
                var unit_id = res[i].id + '_' + unit
                $('#select-unit').append("<option value='" + unit_id + "'>" + unit + "</option>")
            }
        }
    })
})

function previewImagePegawai() {
    var element = document.getElementById("image-preview-pegawai");
        element.classList.remove("d-none");

    document.getElementById("image-preview-pegawai").style.display = "block";
    var oFReader = new FileReader();
     oFReader.readAsDataURL(document.getElementById("image-source-pegawai").files[0]);

    oFReader.onload = function(oFREvent) {
      document.getElementById("image-preview-pegawai").src = oFREvent.target.result;
    };
};

function previewImageKtp() {
    var element = document.getElementById("image-preview-ktp");
        element.classList.remove("d-none");

    document.getElementById("image-preview-ktp").style.display = "block";
    var oFReader = new FileReader();
     oFReader.readAsDataURL(document.getElementById("image-source-ktp").files[0]);

    oFReader.onload = function(oFREvent) {
      document.getElementById("image-preview-ktp").src = oFREvent.target.result;
    };
};

function previewImageKk() {
    var element = document.getElementById("image-preview-kk");
        element.classList.remove("d-none");

    document.getElementById("image-preview-kk").style.display = "block";
    var oFReader = new FileReader();
     oFReader.readAsDataURL(document.getElementById("image-source-kk").files[0]);

    oFReader.onload = function(oFREvent) {
      document.getElementById("image-preview-kk").src = oFREvent.target.result;
    };
};