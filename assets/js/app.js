function copy_text(that) {
    var inp = document.createElement('input');
    document.body.appendChild(inp)
    inp.value = that.value
    inp.select();
    document.execCommand('copy', false);
    inp.remove();
}

function update_auction(data) {
    grecaptcha.ready(function() {
        var formulario = document.getElementById("update_auction");

        grecaptcha.execute(global_vars.recaptcha_v3, { action: 'update_auction' }).then(function(token) {
            formulario.querySelector('input[name="date"]').value = data.value;
            formulario.querySelector('input[name="recaptcha"]').value = token;
            formulario.submit();
        });
    });
}

function update_product(data) { 
    grecaptcha.ready(function() {
        var formulario = document.getElementById("update_product");

        grecaptcha.execute(global_vars.recaptcha_v3, { action: 'update_product' }).then(function(token) {
            formulario.querySelector('input[name="date"]').value = data.value;
            formulario.querySelector('input[name="recaptcha"]').value = token;
            formulario.submit();
        });
    });
}

function renew_api_key(that) {
    that.innerHTML = '<i class="fas fa-sync-alt fa-pulse fa-fw"></i>';
    
    $.get("/dashboard/api/renew", function(res) {
        console.log(res.success);
        if (res.success == true) {
            console.log(res);
            $("#api_key").val(res.api_key);
            that.innerHTML = '<i class="fas fa-sync-alt"></i>';
            return true;
        } else {
            swal("Error!", res.message, "error");
            that.innerHTML = '<i class="fas fa-sync-alt"></i>';
            return false;
        }
    });
};

$("#login-submit").click(function(e) {
    $("#login-submit").html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>');
    grecaptcha.ready(function() {
        grecaptcha.execute(global_vars.recaptcha_v3, {
                action: 'login'
        }).then(function(token) {
            $.post("/login", {
                username: $("#login-username").val(),
                password: $("#login-password").val(),
                recaptcha: token
            }, function(jsonObj) {
                var res = JSON.parse(jsonObj);
                if (res['status'] == "success") {
                    window.location.replace("/");
                    return true;
                } else {
                    swal("Error!", res['msg'], "error");
                    $("#login-submit").html('ENTRAR');
                    return false;
                }
            });
        });
    });
    e.preventDefault();
});