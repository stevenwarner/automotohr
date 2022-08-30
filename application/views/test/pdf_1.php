<!DOCTYPE html>
<html>
<head>
 <title>Adobe Document Services PDF Embed API Sample</title>
 <meta charset="utf-8"/>
 <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
 <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body style="margin: 0px">
 <div id="adobe-dc-view"></div>
 <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
 <script type="text/javascript">
    document.addEventListener("adobe_dc_view_sdk.ready", function()
    {
        var adobeDCView = new AdobeDC.View({clientId: "971bf36400bf4aaaadf613fca6c2cf37", divId: "adobe-dc-view"});
        adobeDCView.previewFile(
       {
          // content:   {location: {url: "https://documentcloud.adobe.com/view-sdk-demo/PDFs/Bodea Brochure.pdf"}},
          // content:   {location: {url: "https://ahrincidents.s3.amazonaws.com/ny+state.pdf"}},
          content:   {location: {url: "http://automotohrattachments.s3.amazonaws.com/BMx-16374-tennessee_2021_benefits_summary_guide--xdl.pdf"}},
          metaData: {fileName: "Bodea Brochure.pdf"}
       });
    });
 </script>
</body>
</html>