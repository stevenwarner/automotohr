<ul>
    <li>
        <input type="text" value="" placeholder="" id="google_drive_resume" readonly name="google_drive_resume" class="form-fields">
        <button class="choose-btn bg-color" onclick="loadPicker();" type="button">Google Drive</button>
    </li>
</ul>
<!--<button class="btn btn-default" onclick="getFileContent();" type="button">Get File Content</button>-->
<input type="hidden" id="resume_url" name="resume_url" value=""/>
<input type="hidden" id="resume_on_aws" name="resume_on_aws" value=""/>

<!-- The Google API Loader script. -->
<script type="text/javascript" src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
<script type="text/javascript" src="https://apis.google.com/js/client.js"></script>


<script type="text/javascript">


    var myUrl = "<?php echo base_url('/home/ajax_responder') ?>";
    var myToken = '';
    var myFileId = '';

    // The Browser API key obtained from the Google Developers Console.
    //var developerKey = 'AIzaSyB114Zx0k-ozhtOsTYkFW7cBZ5q9tDIazA'; Hamid
    var developerKey = 'AIzaSyBCEgBd5711X87DTK9t2tMO1p-YLGxo7rs'; //Dev
    //todo:Make this dynamic Later

    // The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
    //var clientId = "499875028110-ocqp59ssk7lbsvfrd1u1mforvl4ebc46.apps.googleusercontent.com" //Hamid
    var clientId = "145015201408-am8hodqg44j3gi5483494mhjp7tikakj.apps.googleusercontent.com"; //Dev
    //var clientId = "145015201408-a6vejo73c3elhodoue3v6a48np7u1nia.apps.googleusercontent.com"; //Other
    //todo:Make this dynamic Later

    // Scope to use to access user's photos.
    /*//var scope = ['https://www.googleapis.com/auth/photos'];*/
    var scope = ['https://www.googleapis.com/auth/drive'];

    var pickerApiLoaded = false;
    var oauthToken;

    //Start Picker
    function loadPicker() {
        gapi.load('auth', {'callback': onAuthApiLoad});
        gapi.load('picker', {'callback': onPickerApiLoad});
    }

    // Use the API Loader script to load google.picker and gapi.auth.
    /*
     function onApiLoad() {
     gapi.load('auth', {'callback': onAuthApiLoad});
     gapi.load('picker', {'callback': onPickerApiLoad});
     }
     */

    function onAuthApiLoad() {
        window.gapi.auth.authorize(
            {
                'client_id': clientId,
                'scope': scope,
                'immediate': false
            },
            handleAuthResult);
    }

    function onPickerApiLoad() {
        pickerApiLoaded = true;
        createPicker();
    }

    function handleAuthResult(authResult) {
        if (authResult && !authResult.error) {
            oauthToken = authResult.access_token;

            myToken = authResult.access_token;
            loadDriveApi();
            createPicker();

        }
    }

    // Create and render a Picker object for picking user Photos.
    function createPicker() {
        if (pickerApiLoaded && oauthToken) {
            var picker = new google.picker.PickerBuilder().
                addView(google.picker.ViewId.DOCS).
                setOAuthToken(oauthToken).
                setDeveloperKey(developerKey).
                setCallback(pickerCallback).
                build();
            picker.setVisible(true);
        }
    }

    // A simple callback implementation.
    function pickerCallback(data) {
        var url = 'nothing';
        if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
            var doc = data[google.picker.Response.DOCUMENTS][0];
            url = doc[google.picker.Document.URL];

            myFileId = doc.id;

            $('#google_drive_resume').val(doc.name);
            //console.log(doc.id);

            printFile(doc.id);
        }
        var message = 'You picked: ' + url;
        //document.getElementById('result').innerHTML = message;

        if(myFileId != ''){
            getFileContent();
        }

    }


    /**
     * Load Drive API client library.
     */
    function loadDriveApi() {
        /*gapi.client.load('drive', 'v2', listFiles);*/
        gapi.client.load('drive', 'v2');
    }


    function getFileInfo(fileId) {
        var request = gapi.client.drive.files.get({
            'fileId': fileId
        });
    }

    function printFile(fileId) {
        var request = gapi.client.drive.files.get({
            'fileId': fileId
        });
        request.execute(function (resp) {
            /*
            console.log('Title: ' + resp.title);
            console.log('Description: ' + resp.description);
            console.log('MIME type: ' + resp.mimeType);
            console.log(resp.exportLinks['application/rtf']); */

            $('#resume_url').val(resp.exportLinks['application/rtf']);
        });
    }

    function getFileContent() {
        $('.spinner').show();
        $('#mySubmitBtn').attr('disabled', 'disabled');
        var fileUrl = $('#resume_url').val();

        $(document).ajaxStop(function(){
            //console.log('Ajax Stopped.');
        });

        var myRequest = $.ajax({
            url: myUrl,
            data: '&url=' + encodeURI(fileUrl) + '&perform_action=getfilecontent&token=' + myToken + '&document=' + myFileId,
            type: 'POST'
        });

        myRequest.success(function (response) {
            $('#resume_on_aws').val(response);
            $('.spinner').hide();
            $('#mySubmitBtn').removeAttr('disabled');
        });


    }


</script>