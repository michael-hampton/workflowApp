<?php
if ( !$resource_allocator )
{

    $message = "This step requires user assignment. But you dont have the persmission to do that.";
}
else
{

    $message = "This step requires user assignment. Please assign a user to the step.";
}
?>

<br>
<br>
<center>
    <i style="font-size: 72px; color : #ed5565" class=" fa fa-gears"></i>


    <div class="row">
        <p>

        <div class="col-md-8 col-md-offset-2 text-center">
            <h2 style=" color : #ed5565; margin-top: 0;">
                User Assignment
            </h2>
            <br><br>
<?= $message ?>
            <?= $requiredRole ?>
            <br><br>

            <center>

                <select id="assignusers" class="form-control assignusers" name="form[assignusers]" style="width : 250px;">
<?php foreach ($users as $usersKey => $usersValues): ?>
                        <option value="<?= $usersValues["username"] ?> " style="
                                background-repeat: no-repeat;
                                background-size: 30px auto;
                                padding: 10px 0 10px 40px;
                                background-image : url('http://tes-sso.kondor.dev/v2/public/images/users/<?= $usersValues["username"] ?>.jpg')" >
    <?= $usersValues["username"] ?>
                        </option>
                            <?php endforeach; ?>
                </select><br>

                <button id="Assign" class=" btn btn-primary" style="width:250px  " type="button">Assign</button>

            </center>					

        </div>	

        <p>
    </div>

</center>
<br>


<script>

    $ (document).ready (function ()
    {

        $ ("#finish").hide ();
        $ ("#Save").hide ();
        $("#testForm").hide();

        $ ("#Assign").off ();
        $ ("#Assign").on ("click", function ()
        {

            console.log ("MoveOn");
            $.ajax ({
                type: "GET",
                url: "/FormBuilder/tasks/Assign/" + $ ("#assignusers").val () + "/" + $("#projectId").val(),
                success: function (response)
                {

                    showSweetAlert ();
                    $ (".openTasks").click ();

                    $ ("#reviewed").hide ();
                    $ ("#finish").show ();
                    $ ("#Save").show ();
                    return true;
                },
                error: function (request, status, error)
                {
                    console.log ("critical errror occured");
                }
            })

        });

    })
    
    function showSweetAlert() {
            swal({title: "Good job!",
                text: "I will close in 2 seconds.",   
                timer: 2000,
                showConfirmButton: false ,
                type: "success"
            });
        }

</script>