<!-- Main Start -->
<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <header class="hr-header-sec"></header>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">				
                <div class="hr-left-nav">
                    <ul>						
                        <li><a href="javascript:;">Personal Details</a></li>						
                        <li class="active"><a href="javascript:;">Login</a></li>
                        <li><a href="javascript:;">Payment Methods</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                <div class="universal-form-style login-page">
                    <ul>
                        <form>
                            <li class="form-col-100">
                                <label>EMAIL ADDRESS USED AS LOGIN</label>
                                <input class="invoice-fields" type="text" name="email">
                            </li>
                            <li class="form-col-100">
                                <label>PASSWORD</label>
                                <input class="invoice-fields" type="password" name="password" onkeyup="passwordStrength(this.value)">
                                <div class="password-trength-wrp">							
                                    <div id="passwordStrength" >
                                        <div class='pass0 strength0'></div>
                                        <div class='pass1 strength0'></div>
                                        <div class='pass1 strength0'></div>
                                        <div class='pass1 strength0'></div>
                                    </div>									
                                    <div class="passwordDescription" id="passwordDescription">Password not entered</div>
                                </div>	
                            </li>
                            <li class="form-col-100">
                                <label>CONFIRM PASSWORD</label>
                                <input class="invoice-fields" type="password" name="confirm-password">
                            </li>

                            <li class="form-col-100 submit-field">
                                <div class="btn-wrp">
                                    <input class="reg-btn" type="submit" value="save">
                                </div>
                                <div class="btn-wrp">
                                    <input class="reg-btn" type="submit" value="Deactivate Account">
                                </div>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function passwordStrength(password) {
        var desc = new Array();
        desc[0] = "Very Weak";
        desc[1] = "Obvious";
        desc[2] = "Not secure enough";
        desc[3] = "Fair";
        desc[4] = "Strong";
        desc[5] = "Very Strong";

        var toggle_class = new Array();
        toggle_class[0] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div>";
        toggle_class[1] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        toggle_class[2] = "<div class='pass0 strength2'></div><div class='pass1 strength2'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[3] = "<div class='pass0 strength3'></div><div class='pass1 strength3'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[4] = "<div class='pass0 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength0'></div>";
        toggle_class[5] = "<div class='pass0 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div>";

        var score   = 0;

        //if password bigger than 6 give 1 point
        if (password.length > 6) score++;

        //if password has both lower and uppercase characters give 1 point	
        if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

        //if password has at least one number give 1 point
        if (password.match(/\d+/)) score++;

        //if password has at least one special caracther give 1 point
        if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;

        //if password bigger than 12 give another 1 point
        if (password.length > 10) score++;

         document.getElementById("passwordDescription").innerHTML = desc[score];
         document.getElementById("passwordStrength").innerHTML = toggle_class[score];

    }
</script>