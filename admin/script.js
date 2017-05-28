function compileFormData(userid) {

        var groupids = [];

         $('.groupidcell').each(function (i, cell) {

                var string = "" + $(cell).html();

                groupids[i] = string;

    });

        if (groupids.length == 0) {
                groupids[0] = "569e9d51c585f";
        }

    var data = {
                firstname: $("textarea[name=firstname]").val(),
                lastname: $("textarea[name=lastname]").val(),
                email: $("textarea[name=email]").val(),
                password: $("textarea[name=password]").val(),
                id: $("textarea[name=id]").val(),
                verified: $("textarea[name=verification]").val(),
                permissions: $("textarea[name=permissions]").val(),
                date: $("textarea[name=date]").val(),
                groups:groupids,
                accountStatus: $("input[name=accountStatus]").val(),
                lastLogin: $("textarea[name=lastLogin]").val(),
                numViolations: $("textarea[name=numViolations]").val(),
				privacy: $("textarea[name=privacy]").val()
        };
    
    $.ajax({
        type:"POST",
        url:"editUser.php",
        data: $.param(data),
        processData: false,
        success: function (response) {
                //alert(response);
                window.location="http://142.31.53.22/~mdtalks/admin/userControl.php?id=" + userid;
        },
        error: function(response) {
                        alert(response);
                }
    });
}

function banUser() {
        $("#accountStatusText").val("-2");
}

function suspendUser() {
        var milliseconds = prompt("Please enter number of days to suspend user.");

        milliseconds = parseInt(milliseconds);

        milliseconds *= 24 * 60 * 60 * 1000;

        milliseconds += new Date().getTime();

        $("#accountStatusText").val(Math.round(milliseconds / 1000));
}

function unBanUser() {
        $("#accountStatusText").val("-1");
}

function goToUserControl(id) {
	window.location = "http://142.31.53.22/~mdtalks/admin/userControl.php?id=" + id;
}
