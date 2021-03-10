#!/usr/bin/php -q
<?php error_reporting(E_ALL);
//$devEmail = 'ahassan.egenie@gmail.com';
// $devEmail = 'dev@automotohr.com';
// $devEmail = 'mubashir.saleemi123@gmail.com';
/* Read the message from STDIN */
$fd = fopen("php://stdin", "r");
$email = ""; // This will be the variable holding the data.

while (!feof($fd)) {
    $email .= fread($fd, 1024);
}
$messageArray = [];
$messageArray['outlook'] = 'From mubashir.saleemi@outlook.com Tue Mar 09 22:38:05 2021
Received: from mail-oln040092253045.outbound.protection.outlook.com ([40.92.253.45]:13609 helo=APC01-SG2-obe.outbound.protection.outlook.com)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384
        (Exim 4.94)
        (envelope-from <mubashir.saleemi@outlook.com>)
        id 1lJsTr-0000CW-Bz
        for notifications@automotohr.com; Tue, 09 Mar 2021 22:38:05 -0800
ARC-Seal: i=1; a=rsa-sha256; s=arcselector9901; d=microsoft.com; cv=none;
 b=GwS9ScpIiXX5R9rsLLFZLLaD/EGRxFTLMK4jGMcyE+UOFJZjRMhUhi1TiFbsNcNPsOvI8snpzJMH5ebF6rnzA2TmcVwgKKkdOCghuJLj92LT//VkL9HSfGLBUtDW8wAWuYRACzTxudrJN5fv2SWuQqZIK9DlBs+DbU6h+ZWy3QsN66+iRA4HIkn/tLUmIjIH9eNtaFrQQqQsuZm0+3nnJz1jIZEMmNxi6YKUgQI68zUZcx0fYY6JtbpFbuX1a1+hhU35cOvZx9B8WuS6dlNd2MkeGwMbnsRLmptsK9KMNPAoGCkvHwW2BTKvbvfXxGNQxzi69/tMz9dTOPkvqS8rWQ==
ARC-Message-Signature: i=1; a=rsa-sha256; c=relaxed/relaxed; d=microsoft.com;
 s=arcselector9901;
 h=From:Date:Subject:Message-ID:Content-Type:MIME-Version:X-MS-Exchange-SenderADCheck;
 bh=dVKINDlJrR3ZxOL7gayAxnjxoBMWf7nA9RWImp7AlNY=;
 b=Z7+iR2JClpT2LlfmAs4eBN/Ir5GwzEaZhTdbTj6azEB721EDtlJGpdH2buYIEJos4xYKqWtyK6ZnSs5E5TvCTh66njOcgllmH0LzDDCpyFvnKZHCwHj9KFabpMCsOden4NjefwAfRqR1VV6BO0OvMsanQtIauvhHxb1B31fpjbQ/OlEC+L1I+BuinZvTUBm6esHp9wE/LlcZ12ljePtELk6Kv9pjFcnV6EKjaPaHkSifEssSNwoLdMDFhqqtYFEveoS6l1NPDZC+/7rvF3N3SA49dvfsZ7GWsqNaqfG4e1CmV6UNTFykgYTz7uZksHH1uCX06ZkWSofK8Jsh5AwF/w==
ARC-Authentication-Results: i=1; mx.microsoft.com 1; spf=none; dmarc=none;
 dkim=none; arc=none
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed; d=outlook.com;
 s=selector1;
 h=From:Date:Subject:Message-ID:Content-Type:MIME-Version:X-MS-Exchange-SenderADCheck;
 bh=dVKINDlJrR3ZxOL7gayAxnjxoBMWf7nA9RWImp7AlNY=;
 b=jike0TwQfk1c49H/XAuyRmkj7wS9J+yNuZVHVL0FOd/sdpOnXilJuBXg4mz0UDrNnBY8OUH/59meztdaEWAYh2qXLIvvHQ1sxoVgxgapuO+tnpyBB2ix2zYfl5OYmUcqmtSsugHYx+p8lsxw1FwgBGKV8/YJ1rJbtHZ4PeDQGRDfvlpayWbRLk4su7fMepzJDGXn1hmOMiTJGNZQPkBO7BBh1OfZpEEI2TYGR1u3m6gUpuSxtmAPdCZCTAGouiWwWKVE2TNSNj5p/IRfrhOAxrJwfJ4t/sPdiO6Pz//cn1Ztv1MdKIdZ+uGe86o6BRwy5ElrYv1AXkFygsVz6Xr05A==
Received: from TY2PR0101MB2255.apcprd01.prod.exchangelabs.com
 (2603:1096:404:61::13) by TYZPR01MB3919.apcprd01.prod.exchangelabs.com
 (2603:1096:400:8::9) with Microsoft SMTP Server (version=TLS1_2,
 cipher=TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384) id 15.20.3912.19; Wed, 10 Mar
 2021 06:37:20 +0000
Received: from TY2PR0101MB2255.apcprd01.prod.exchangelabs.com
 ([fe80::f5a3:8e29:764b:a3e]) by
 TY2PR0101MB2255.apcprd01.prod.exchangelabs.com ([fe80::f5a3:8e29:764b:a3e%6])
 with mapi id 15.20.3890.040; Wed, 10 Mar 2021 06:37:20 +0000
From: Mubashir Ahmed <mubashir.saleemi@outlook.com>
To: "notifications@automotohr.com" <notifications@automotohr.com>
Subject: Re: Reply to this email
Thread-Topic: Reply to this email
Thread-Index: AQHXFRUJRWVJUcG740qU3XGh+VQ5pap8xXPj
Date: Wed, 10 Mar 2021 06:37:20 +0000
Message-ID:
 <TY2PR0101MB22558A30BCF50B558C0B54AF93919@TY2PR0101MB2255.apcprd01.prod.exchangelabs.com>
References:
 <0111017818531e4e-c437be65-c06f-4c10-8501-a0cdd5d19d01-000000@us-west-1.amazonses.com>
In-Reply-To:
 <0111017818531e4e-c437be65-c06f-4c10-8501-a0cdd5d19d01-000000@us-west-1.amazonses.com>
Accept-Language: en-US
Content-Language: en-US
X-MS-Has-Attach:
X-MS-TNEF-Correlator:
x-tmn: [L/jni6pgv8/i2jZ3xDKrAhufqSXsZdyu]
x-ms-publictraffictype: Email
x-ms-office365-filtering-correlation-id: bd3cddfa-914f-4a46-ce25-08d8e38ef4d9
x-ms-traffictypediagnostic: TYZPR01MB3919:
x-microsoft-antispam: BCL:0;
x-microsoft-antispam-message-info:
 vXJcsMmVfI6akJN4NCTRePzMS+43wQzOTXNw2wfB1T9BXOSZCcppzIVfLHBy2oPlLEQ1/ntAawZ2pUsKa4N067qk+G4vqzs6vQvHgkx0gtPDKs8XOB+g1RQTqW9osHyXwhMBHRBNtKqKbHKVxo2ok5JDwarrroQY1r4Yxl/CjoDqUlTRokKe7kOZSYFo+1VyughzpN+7kIVtzcM+vHkvllM2xqY0wMJ9XAKeSl9gZGF9FGpifmbyyAjzgVXcMETMQbRujpCgOkeXzldaYTtamUipRwaS+w9+YbjdViwg7DMy4hBbH9q3JYfdqfe1tNCOzsnKUUMedeM+7eQh54ctxjThJFevMY9UbIoH9BYLGgHEXlQkiH8Oot7VvD1AOhUTNVRefpU2pY1et5ETjH7l9CoT+a8V6gc00C8GcFJkpYU=
x-ms-exchange-antispam-messagedata:
 evvIZ5GkFLU8VtjuXmmDb/NpLykOECgvxweMjxjMaJ7ktavE90sVp1+jdF/XilE0cKtQWMdH3+OFCUoxrjN0JH1N8eTxr35PJYVtjbpGgIQnEZ4XOrx2liLQ080zuCUBeZerDXbckO0b6Tv9ygDBQg==
x-ms-exchange-transport-forked: True
Content-Type: multipart/alternative;
        boundary="_000_TY2PR0101MB22558A30BCF50B558C0B54AF93919TY2PR0101MB2255_"
MIME-Version: 1.0
X-OriginatorOrg: outlook.com
X-MS-Exchange-CrossTenant-AuthAs: Internal
X-MS-Exchange-CrossTenant-AuthSource: TY2PR0101MB2255.apcprd01.prod.exchangelabs.com
X-MS-Exchange-CrossTenant-RMS-PersistedConsumerOrg: 00000000-0000-0000-0000-000000000000
X-MS-Exchange-CrossTenant-Network-Message-Id: bd3cddfa-914f-4a46-ce25-08d8e38ef4d9
X-MS-Exchange-CrossTenant-originalarrivaltime: 10 Mar 2021 06:37:20.5211
 (UTC)
X-MS-Exchange-CrossTenant-fromentityheader: Hosted
X-MS-Exchange-CrossTenant-id: 84df9e7f-e9f6-40af-b435-aaaaaaaaaaaa
X-MS-Exchange-CrossTenant-rms-persistedconsumerorg: 00000000-0000-0000-0000-000000000000
X-MS-Exchange-Transport-CrossTenantHeadersStamped: TYZPR01MB3919
X-Spam-Status: No, score=-2.1
X-Spam-Score: -20
X-Spam-Bar: --
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  Hdjsjs Dodd Ddd Get Outlook for Android<https://aka.ms/ghei36>
    From: Dev AutomotoHR Sent: Tuesday, March 9, 2021 11:50:03 PM To: mubashir.saleemi@outlook.com
    Subject: Reply to thi [...]
 Content analysis details:   (-2.1 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
 -1.9 BAYES_00               BODY: Bayes spam probability is 0 to 1%
                             [score: 0.0000]
 -0.0 SPF_PASS               SPF: sender matches SPF record
  0.0 FREEMAIL_FROM          Sender email is commonly abused enduser mail
                             provider
                             [mubashir.saleemi[at]outlook.com]
 -0.0 SPF_HELO_PASS          SPF: HELO matches SPF record
  0.0 HTML_MESSAGE           BODY: HTML included in message
  0.0 HTML_FONT_LOW_CONTRAST BODY: HTML font color similar or
                             identical to background
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
 -0.1 DKIM_VALID             Message has at least one valid DKIM or DK signature
 -0.1 DKIM_VALID_EF          Message has a valid DKIM or DK signature from
                             envelope-from domain
 -0.1 DKIM_VALID_AU          Message has a valid DKIM or DK signature from
                             author"s domain
  0.0 KAM_SHORT              Use of a URL Shortener for very short URL
X-Spam-Flag: NO

--_000_TY2PR0101MB22558A30BCF50B558C0B54AF93919TY2PR0101MB2255_
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: quoted-printable

Hdjsjs
Dodd


Ddd

Get Outlook for Android<https://aka.ms/ghei36>
________________________________
From: Dev AutomotoHR <notifications@automotohr.com>
Sent: Tuesday, March 9, 2021 11:50:03 PM
To: mubashir.saleemi@outlook.com <mubashir.saleemi@outlook.com>
Subject: Reply to this email

Dev AutomotoHR
Dear Mubashir Ahmed,


devteam has sent you a private message.

Date: 2021-03-09 10:50:02

Subject: Reply to this email
________________________________

Email will come from outlook. just testing



=A9 2021 devsupport3.automotohr.com. All Rights Reserved.<http://devsupport=
3.automotohr.com>

message_id:nKdGM4tNo1aJMmuySIKhdEQsHV5nWyrjjF06JtT8u4ShqmPj5z__

--_000_TY2PR0101MB22558A30BCF50B558C0B54AF93919TY2PR0101MB2255_
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: quoted-printable

<html>
<head>
<meta http-equiv=3D"Content-Type" content=3D"text/html; charset=3Diso-8859-=
1">
</head>
<body>
<div style=3D"color: rgb(33, 33, 33); background-color: rgb(255, 255, 255);=
 text-align: left;" dir=3D"auto">
Hdjsjs</div>
<div style=3D"color: rgb(33, 33, 33); background-color: rgb(255, 255, 255);=
 text-align: left;" dir=3D"auto">
Dodd</div>
<div style=3D"color: rgb(33, 33, 33); background-color: rgb(255, 255, 255);=
 text-align: left;" dir=3D"auto">
<br>
</div>
<div style=3D"color: rgb(33, 33, 33); background-color: rgb(255, 255, 255);=
 text-align: left;" dir=3D"auto">
<br>
</div>
<div style=3D"color: rgb(33, 33, 33); background-color: rgb(255, 255, 255);=
 text-align: left;" dir=3D"auto">
Ddd</div>
<div id=3D"ms-outlook-mobile-signature">
<div><br>
</div>
Get <a href=3D"https://aka.ms/ghei36">Outlook for Android</a></div>
<hr style=3D"display:inline-block;width:98%" tabindex=3D"-1">
<div id=3D"divRplyFwdMsg" dir=3D"ltr"><font face=3D"Calibri, sans-serif" st=
yle=3D"font-size:11pt" color=3D"#000000"><b>From:</b> Dev AutomotoHR &lt;no=
tifications@automotohr.com&gt;<br>
<b>Sent:</b> Tuesday, March 9, 2021 11:50:03 PM<br>
<b>To:</b> mubashir.saleemi@outlook.com &lt;mubashir.saleemi@outlook.com&gt=
;<br>
<b>Subject:</b> Reply to this email</font>
<div>&nbsp;</div>
</div>
<div>
<div class=3D"x_content" style=3D"font-size:100%; line-height:1.6em; displa=
y:block; max-width:1000px; margin:0 auto; padding:0">
<div style=3D"width:100%; float:left; padding:5px 20px; text-align:center; =
box-sizing:border-box; background-color:#000">
<h2 style=3D"color:#fff">Dev AutomotoHR</h2>
</div>
<div class=3D"x_body-content" style=3D"width:100%; float:left; padding:20px=
 0; box-sizing:padding-box">
<h2 style=3D"width:100%; margin:0 0 20px 0">Dear Mubashir Ahmed,</h2>
<br>
<br>
devteam has sent you a private message.<br>
<br>
<b>Date:</b> 2021-03-09 10:50:02<br>
<br>
<b>Subject:</b> Reply to this email<br>
<hr>
<p>Email will come from outlook. just testing</p>
<br>
<br>
</div>
<div class=3D"x_footer" style=3D"width:100%; float:left; background-color:#=
000; padding:20px 30px; box-sizing:border-box">
<div style=3D"float:left; width:100%">
<p style=3D"color:#fff; float:left; text-align:center; font-style:italic; l=
ine-height:normal; font-family:">
<a href=3D"http://devsupport3.automotohr.com" style=3D"color:#fff; text-dec=
oration:none">=A9 2021 devsupport3.automotohr.com. All Rights Reserved.</a>=
</p>
</div>
</div>
</div>
<div style=3D"width:100%; float:left; background-color:#000; color:#000; bo=
x-sizing:border-box">
message_id:nKdGM4tNo1aJMmuySIKhdEQsHV5nWyrjjF06JtT8u4ShqmPj5z__</div>
</div>
</body>
</html>

--_000_TY2PR0101MB22558A30BCF50B558C0B54AF93919TY2PR0101MB2255_--';
$messageArray['gmail'] = 'From twismay15@gmail.com Wed Feb 10 16:46:11 2021
Received: from mail-lj1-f182.google.com ([209.85.208.182]:34773)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256
        (Exim 4.93)
        (envelope-from <twismay15@gmail.com>)
        id 1lA07W-00021h-90
        for notifications@automotohr.com; Wed, 10 Feb 2021 16:46:11 -0800
Received: by mail-lj1-f182.google.com with SMTP id r23so5410105ljh.1
        for <notifications@automotohr.com>; Wed, 10 Feb 2021 16:45:50 -0800 (PST)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=gmail.com; s=20161025;
        h=mime-version:references:in-reply-to:from:date:message-id:subject:to;
        bh=BQ0TgZ7QHn/ez8vNMBL1hqB2UDbhJYXkPa1Rek4VC34=;
        b=JWaWJQSvBWoQ/UIXG6teLnQ/gkRwbxQBu2UDuMZh/W93eFfjQwwVw4xhshrvsGhROG
         Bn3QThwC04NiIvZsz9ceCvLrLiOlzXM/55m+uPTnkkT/rsR07nYMbTItxPqSB9jaYaFG
         ysv8cSF0rYmtqTLluna8oJORVRmQ5KjjYif28kwtQakz7rk2P3fEfx+Crxkve/itgupD
         V9EJfG7ywJh64VXmd96CUqmOpD254aTiFCJraeys+muzjjxBDIFwj990Z21K7eW439L7
         BO59EUUhpOgnWhLctS/7lL5jRJ2r37BsTduTCXK4xrFjlt6P5QhB4OjfpyqLuRvsQrcX
         XZPQ==
X-Google-DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=1e100.net; s=20161025;
        h=x-gm-message-state:mime-version:references:in-reply-to:from:date
         :message-id:subject:to;
        bh=BQ0TgZ7QHn/ez8vNMBL1hqB2UDbhJYXkPa1Rek4VC34=;
        b=avTeDk7fNFZpmEMfbi0IoPWXfqKjBMSukJOk9uJZmxPkw7qTRVznCzqOpjz7C4xAa9
         ys3fnwaSgn98DG0eDiobCmsnjNKw70S12bqooGzeiSsmTZKZCbF/bqUHOQc/9Jz5nCsR
         rwsJtBr9x4HxEuw1GzONlqWGX5BCYs7BHVqErSbq9K2xwrxOqBLrBNOkOmaGoyeWFRYk
         4f+ZbuHFiA9mEpp3uZQP8rMGvN/w/pVzFOg5Qn+rtVu2qEqOpOQle03M98z0kDf2mPlb
         7ydiJib0FZiUqD/v7YXPkaHHtWRmYzfsivCLV9vMOu56x5jV3PxNpgApk10wC9n08Q+4
         ImXA==
X-Gm-Message-State: AOAM530UzLW+yjMJoMdHqzusS8ydS82LhKX/XVa06I39gOXpFZVUsyew
        sK7zX9GSk444L3zCFNIZrS0v97SxGCrpBFUu9Z6wmLs9mUA=
X-Google-Smtp-Source: ABdhPJxkZGL6gC0AjfaZbTJKlpFfdE+3TBvSGTcsz3fi8SvRuyXMdebeGdIVUgV0bXVSdhoFz6UO8QrCVU6eA3b/R9o=
X-Received: by 2002:a2e:a410:: with SMTP id p16mr3616595ljn.154.1613004327660;
 Wed, 10 Feb 2021 16:45:27 -0800 (PST)
MIME-Version: 1.0
References: <011101778e844f92-1663ab9d-04f9-4c5b-849b-4bb4f53f1130-000000@us-west-1.amazonses.com>
In-Reply-To: <011101778e844f92-1663ab9d-04f9-4c5b-849b-4bb4f53f1130-000000@us-west-1.amazonses.com>
From: Platinum Plus <twismay15@gmail.com>
Date: Wed, 10 Feb 2021 16:45:15 -0800
Message-ID: <CACH7rrhtcqSwFp2yzwaf7KZM-Qwv2cq1WTKMyptaT2BFwP2gSQ@mail.gmail.com>
Subject: Re: Job Application received at Mercedes Benz Of Anaheim
To: notifications@automotohr.com
Content-Type: multipart/alternative; boundary="000000000000f8b26f05bb04d57c"
X-Spam-Status: No, score=0.1
X-Spam-Score: 1
X-Spam-Bar: /
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  Thank you for your response. On Wed, Feb 10, 2021, 4:36 PM
    Mercedes Benz Of Anaheim < wrote: > Mercedes-Benz of Anaheim > > Subject:
    Job Application received at Mercedes Benz Of Anaheim > > Dear Anthony Jackson:
    > > We appreciate your interest in Mercedes Benz Of Anaheim and the position
    > of [...]
 Content analysis details:   (0.1 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
 -0.0 BAYES_40               BODY: Bayes spam probability is 20 to 40%
                             [score: 0.3890]
 -0.0 SPF_PASS               SPF: sender matches SPF record
  0.2 FREEMAIL_ENVFROM_END_DIGIT Envelope-from freemail username ends
                             in digit
                             [twismay15[at]gmail.com]
  0.0 FREEMAIL_FROM          Sender email is commonly abused enduser mail
                             provider
                             [twismay15[at]gmail.com]
  0.0 HTML_FONT_LOW_CONTRAST BODY: HTML font color similar or
                             identical to background
  0.0 HTML_MESSAGE           BODY: HTML included in message
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
 -0.1 DKIM_VALID             Message has at least one valid DKIM or DK signature
 -0.1 DKIM_VALID_AU          Message has a valid DKIM or DK signature from
                             author"s domain
 -0.1 DKIM_VALID_EF          Message has a valid DKIM or DK signature from
                             envelope-from domain
X-Spam-Flag: NO

--000000000000f8b26f05bb04d57c
Content-Type: text/plain; charset="UTF-8"

Thank you for your response.

On Wed, Feb 10, 2021, 4:36 PM Mercedes Benz Of Anaheim <
notifications@automotohr.com> wrote:

> Mercedes-Benz of Anaheim
>
> Subject: Job Application received at Mercedes Benz Of Anaheim
>
> Dear Anthony Jackson:
>
> We appreciate your interest in Mercedes Benz Of Anaheim and the position
> of Car Wash Lot Attendant Mercedes-Benz of Anaheim for which you applied.
> We are reviewing applications currently and expect to schedule interviews
> in the next couple of weeks. If you are selected for the next phase of the
> recruitment process, you will be contacted by our Human Resources staff for
> an interview session.
> Thank you, again, for your interest in Mercedes Benz Of Anaheim.
> We do appreciate the time you invested in this application.
>
> Regards,
> Mercedes Benz Of Anaheim Hiring team
>
> mercedes-benz-of-anaheim.automotohr.com
> message_id:u2Aaekxozttcz1ZpRQXwzrDVTgwo9czEfaOtumS4QlgpngOe__
>

--000000000000f8b26f05bb04d57c
Content-Type: text/html; charset="UTF-8"
Content-Transfer-Encoding: quoted-printable

<div dir=3D"auto">Thank you for your response.</div><br><div class=3D"gmail=
_quote"><div dir=3D"ltr" class=3D"gmail_attr">On Wed, Feb 10, 2021, 4:36 PM=
 Mercedes Benz Of Anaheim &lt;<a href=3D"mailto:notifications@automotohr.co=
m">notifications@automotohr.com</a>&gt; wrote:<br></div><blockquote class=
=3D"gmail_quote" style=3D"margin:0 0 0 .8ex;border-left:1px #ccc solid;padd=
ing-left:1ex"><div style=3D"font-size:100%;line-height:1.6em;display:block;=
max-width:1000px;margin:0 auto;padding:0"><div style=3D"width:100%;float:le=
ft;padding:5px 20px;text-align:center;box-sizing:border-box;background-colo=
r:#000"><h2 style=3D"color:#fff">Mercedes-Benz of Anaheim</h2></div> <div s=
tyle=3D"width:100%;float:left;padding:20px 20px 60px 20px;box-sizing:border=
-box;background:url(http://images/bg-body.jpg)"><p>Subject: Job Application=
 received at Mercedes Benz Of Anaheim</p><p>Dear Anthony Jackson:</p>
                                                                           =
        <p>We appreciate your interest in Mercedes Benz Of Anaheim and the =
position of Car Wash Lot Attendant Mercedes-Benz of Anaheim for which you a=
pplied. We are reviewing applications currently and expect to schedule inte=
rviews in the next couple of weeks. If you are selected for the next phase =
of the recruitment process, you will be contacted by our Human Resources st=
aff for an interview session.<br>
                                                                           =
         Thank you, again, for your interest in Mercedes Benz Of Anaheim.<b=
r>
                                                                           =
         We do appreciate the time you invested in this application.</p>
                                                                           =
        <p>Regards,<br>
                                                                           =
        Mercedes Benz Of Anaheim Hiring team</p></div><div style=3D"width:1=
00%;float:left;background-color:#000;padding:20px 30px;box-sizing:border-bo=
x"><div style=3D"float:left;width:100%"><p><a style=3D"color:#fff;text-deco=
ration:none" href=3D"http://mercedes-benz-of-anaheim.automotohr.com" target=
=3D"_blank" rel=3D"noreferrer">mercedes-benz-of-anaheim.automotohr.com</a><=
/p></div></div></div><div style=3D"width:100%;float:left;background-color:#=
000;color:#000;box-sizing:border-box">message_id:u2Aaekxozttcz1ZpRQXwzrDVTg=
wo9czEfaOtumS4QlgpngOe__</div>

</blockquote></div>

--000000000000f8b26f05bb04d57c--';
$messageArray['mail'] = 'From egenie.apple@gmail.com Tue Mar 09 23:06:39 2021
Received: from mail-wr1-f49.google.com ([209.85.221.49]:36843)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256
        (Exim 4.94)
        (envelope-from <egenie.apple@gmail.com>)
        id 1lJsvV-0001Hp-V0
        for notifications@automotohr.com; Tue, 09 Mar 2021 23:06:39 -0800
Received: by mail-wr1-f49.google.com with SMTP id u14so21742549wri.3
        for <notifications@automotohr.com>; Tue, 09 Mar 2021 23:06:17 -0800 (PST)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=gmail.com; s=20161025;
        h=content-transfer-encoding:from:mime-version:subject:date:message-id
         :references:in-reply-to:to;
        bh=T31iRKDXs3QoiCrNFngVBmlhHTwCNaH/0qcsRr/6+W8=;
        b=KbhIFvU6oD61i2HliE2q2Jlwd1Di7KqSjQLe84aNNWSpaPiizHhsNW39lLdLZqlbTV
         32plcze1TxYqWW9kRqaawIHCwZNLZ6bNf9dwEkfYmycax4YtIZMPNmwglYuMZvv6pJb7
         Fv1xZhEB2tedRncaMcPXY1GmmgzLYJYu4MW0A5wZx1EgeH3QSVUsk74ui6Nyok1OaN8j
         ICP1xE6x7lpSf1hKKY3WjNQwPxLKcod8ae6fM1+YwU2L4w77nas1xu9xVQVQjvvlvcle
         Sxy67rb1qnw89nLp9d7/3r+BdHc++arxn10MYNAIfV+WoAXXw+k+irezBS7uSB2+/ODN
         nonw==
X-Google-DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=1e100.net; s=20161025;
        h=x-gm-message-state:content-transfer-encoding:from:mime-version
         :subject:date:message-id:references:in-reply-to:to;
        bh=T31iRKDXs3QoiCrNFngVBmlhHTwCNaH/0qcsRr/6+W8=;
        b=mSjF6K9C/+yFK9eXYfAJp6AuQDqteKa0PPKkqKeVj4fyBov/dGgwdyo6UBmRijw1ph
         NmC3f5CKjPPs6104/05+Xrl4tBcko6OeXjb2JbX11/KmXCEjfdx6/q2LzBag1rp5rKMD
         SwuQuvveVLhjXvKTWvmcrlNk7D+brGe1Vk6nuhs+Ppv2ChTejqmISCSAuaDSoEAFCcBj
         jW1f65LKz0KB8WBCDYllyNxZISKLD8qJtdfagC3ENaF+2atac8+w9pbU4vuTnGZYtxy3
         wVL9M8SFKpltTjV01DoFsdrWlJSwZwaPfoCgp6qkn9qNW+ClGull0gzc5gNiElb49vGa
         JjDQ==
X-Gm-Message-State: AOAM530QzokVj0UKS/kV4PtuJimaPA37cJlQ6wPxnf54u2sKC9596KVr
        56pY2DlNvRA+05z65TzPOKCW4ZTjgXI=
X-Google-Smtp-Source: ABdhPJweeXT+FB5gmghSGbnyJOCIBOQEP9blEF0ZVA13YFRTamh75WMyh/1m+xM7EL0AsSeWd3NbUw==
X-Received: by 2002:a5d:4203:: with SMTP id n3mr1834387wrq.116.1615359955843;
        Tue, 09 Mar 2021 23:05:55 -0800 (PST)
Received: from [192.168.20.171] ([72.255.38.246])
        by smtp.gmail.com with ESMTPSA id g5sm12761014wrq.30.2021.03.09.23.05.55
        for <notifications@automotohr.com>
        (version=TLS1_3 cipher=TLS_AES_128_GCM_SHA256 bits=128/128);
        Tue, 09 Mar 2021 23:05:55 -0800 (PST)
Content-Type: multipart/alternative; boundary=Apple-Mail-BD689107-68E7-49F6-9466-B17B8C9574C2
Content-Transfer-Encoding: 7bit
From: Egenie Next <egenie.apple@gmail.com>
Mime-Version: 1.0 (1.0)
Subject: Re: From AHR
Date: Wed, 10 Mar 2021 12:05:53 +0500
Message-Id: <C8876C11-FD6E-486D-A3DA-A90E4443E8F3@gmail.com>
References: <CAHaL5ja=c2sf7cSaF83Y8DTxuYcvgVAd0_UUVznDRTQij9jOrg@mail.gmail.com>
In-Reply-To: <CAHaL5ja=c2sf7cSaF83Y8DTxuYcvgVAd0_UUVznDRTQij9jOrg@mail.gmail.com>
To: notifications@automotohr.com
X-Mailer: iPhone Mail (18D52)
X-Spam-Status: No, score=0.6
X-Spam-Score: 6
X-Spam-Bar: /
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  Replied from mailclient D D D D Ddff
 Content analysis details:   (0.6 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
  0.8 BAYES_50               BODY: Bayes spam probability is 40 to 60%
                             [score: 0.5000]
 -0.0 SPF_PASS               SPF: sender matches SPF record
  0.0 FREEMAIL_FROM          Sender email is commonly abused enduser mail
                             provider
                             [egenie.apple[at]gmail.com]
  0.0 HTML_MESSAGE           BODY: HTML included in message
  0.0 HTML_FONT_LOW_CONTRAST BODY: HTML font color similar or
                             identical to background
  0.0 MIME_QP_LONG_LINE      RAW: Quoted-printable line longer than 76
                             chars
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
 -0.1 DKIM_VALID             Message has at least one valid DKIM or DK signature
 -0.1 DKIM_VALID_EF          Message has a valid DKIM or DK signature from
                             envelope-from domain
 -0.1 DKIM_VALID_AU          Message has a valid DKIM or DK signature from
                             author"s domain
X-Spam-Flag: NO


--Apple-Mail-BD689107-68E7-49F6-9466-B17B8C9574C2
Content-Type: text/plain;
        charset=utf-8
Content-Transfer-Encoding: quoted-printable

Replied from mailclient
D
D

D
D

Ddff

Sent from my iPhone

> On 10-Mar-2021, at 12:03 PM, Egenie Next <egenie.apple@gmail.com> wrote:
>=20
> =EF=BB=BF
>=20
>=20
>> On Wed, 10 Mar 2021 at 12:02 PM, Dev AutomotoHR <notifications@automotohr=
.com> wrote:
>> Dev AutomotoHR
>>=20
>> Dear Egenie Apple,
>>=20
>>=20
>>=20
>> devteam has sent you a private message.
>>=20
>> Date: 2021-03-09 23:02:39
>>=20
>> Subject: =46rom AHR
>> please reply
>>=20
>>=20
>>=20
>> =C2=A9 2021 devsupport3.automotohr.com. All Rights Reserved.
>>=20
>> message_id:3qUu2jvIev8BVzVOaFBQDPHjKQ5nzUfDka7muD5I8ej3NfRYUt__

--Apple-Mail-BD689107-68E7-49F6-9466-B17B8C9574C2
Content-Type: text/html;
        charset=utf-8
Content-Transfer-Encoding: quoted-printable

<html><head><meta http-equiv=3D"content-type" content=3D"text/html; charset=3D=
utf-8"></head><body dir=3D"auto">Replied from mailclient<div>D</div><div>D</=
div><div><br></div><div>D</div><div>D</div><div><br></div><div>Ddff<br><br><=
div dir=3D"ltr">Sent from my iPhone</div><div dir=3D"ltr"><br><blockquote ty=
pe=3D"cite">On 10-Mar-2021, at 12:03 PM, Egenie Next &lt;egenie.apple@gmail.=
com&gt; wrote:<br><br></blockquote></div><blockquote type=3D"cite"><div dir=3D=
"ltr">=EF=BB=BF<div><br></div><div><br><div class=3D"gmail_quote"><div dir=3D=
"ltr" class=3D"gmail_attr">On Wed, 10 Mar 2021 at 12:02 PM, Dev AutomotoHR &=
lt;<a href=3D"mailto:notifications@automotohr.com">notifications@automotohr.=
com</a>&gt; wrote:<br></div><blockquote class=3D"gmail_quote" style=3D"margi=
n:0 0 0 .8ex;border-left:1px #ccc solid;padding-left:1ex"><div style=3D"font=
-size:100%;line-height:1.6em;display:block;max-width:1000px;margin:0 auto;pa=
dding:0"><div style=3D"width:100%;float:left;padding:5px 20px;text-align:cen=
ter;box-sizing:border-box;background-color:#000"><h2 style=3D"color:#fff">De=
v AutomotoHR</h2></div>  <div style=3D"width:100%;float:left;padding:20px 0;=
box-sizing:padding-box"><h2 style=3D"width:100%;margin:0 0 20px 0">Dear Egen=
ie Apple,</h2><br><br>devteam has sent you a private message.<br><br><b>Date=
:</b> 2021-03-09 23:02:39<br><br><b>Subject:</b> =46rom AHR<br><hr><p>please=
 reply</p><br><br></div><div style=3D"width:100%;float:left;background-color=
:#000;padding:20px 30px;box-sizing:border-box"><div style=3D"float:left;widt=
h:100%"><p><a style=3D"color:#fff;text-decoration:none" href=3D"http://devsu=
pport3.automotohr.com" target=3D"_blank">=C2=A9 2021 devsupport3.automotohr.=
com. All Rights Reserved.</a></p></div></div></div><div style=3D"width:100%;=
float:left;background-color:#000;color:#000;box-sizing:border-box">message_i=
d:3qUu2jvIev8BVzVOaFBQDPHjKQ5nzUfDka7muD5I8ej3NfRYUt__</div>
</blockquote></div></div>
</div></blockquote></div></body></html>=

--Apple-Mail-BD689107-68E7-49F6-9466-B17B8C9574C2--';

$messageArray['iphonegmail'] = 'From egenie.apple@gmail.com Tue Mar 09 23:05:12 2021
Received: from mail-ot1-f48.google.com ([209.85.210.48]:32841)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256
        (Exim 4.94)
        (envelope-from <egenie.apple@gmail.com>)
        id 1lJsu7-0001AJ-Dp
        for notifications@automotohr.com; Tue, 09 Mar 2021 23:05:12 -0800
Received: by mail-ot1-f48.google.com with SMTP id j8so15468961otc.0
        for <notifications@automotohr.com>; Tue, 09 Mar 2021 23:04:51 -0800 (PST)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=gmail.com; s=20161025;
        h=mime-version:references:in-reply-to:from:date:message-id:subject:to;
        bh=+f4hqmhrwVbNQNJ4yfwEuUjyKQKS2Z4Thb2vXZioS7o=;
        b=O6IwDmOEwkkeuL5wrtQgnqwKh7xs4W+JxQqGmReinrRzMCcpdiRODJsYLPdZNFetmf
         zEhloOtKHuvIfIZ8O7/f4uBnXSnjlC9TplW9s5eMXjwzw54PU3Y1bzNT7b8VtirO53Fu
         KZK2CZYTGdjGf0fVIN16U0JswxWRc6KgZj04jUX+Ozlt/JM24LJF4c/8XCDcpe5FlxwK
         rCOlnzmYvC20fN0YV6jsk03/bVwtLcPezbpbBPHKA5pQ33RdVatMUXf3WDImdK1zMEDx
         lj33tVyv9i8aTg144h/d2IqTi0x+yZCJler9Q+2t/s7dfD5aBUlZV8+4Vatd9d+9vbRH
         /brw==
X-Google-DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=1e100.net; s=20161025;
        h=x-gm-message-state:mime-version:references:in-reply-to:from:date
         :message-id:subject:to;
        bh=+f4hqmhrwVbNQNJ4yfwEuUjyKQKS2Z4Thb2vXZioS7o=;
        b=IZF8ZzKiLAm5Qrx5adZSzAIpatGDOn/Tjhlij1mst16W5DrYYEGAwbLDkqyJO4Ur1T
         RDZvhGhh3jWAilFwl5LGi+3dVItO47lcfZvEepWFO5bCTi0KidgwQTs3VpU+R7XOZNiN
         Q0rscw0e0yvxaCHS4rUcBOtoKHJZbK5WbgjSrMtp6OvIiNaS6w5u9hCJPx0RQWRoguJs
         pjt0ekYhkp60ek9EQVRumkR/epcHxUqi/Hgh3/xQiwgsl0VWe7SsMLZLDW0w/MR4BEFM
         UYyT7KFcTIGZFSCdNIOlNEl99NnskYRx/cukH6BGU5OrY4lFb6B5QerNPr5FAmn4Wu7N
         fWOw==
X-Gm-Message-State: AOAM531O5IXcswHo68pYZQkPQwPIBOqzTtyMSxoJwGR0I5sV2g2NUC04
        YXwlVSkwmGKNO/KTZrAF8Op2ib5TD3Ny7k/+WBsyjy2A
X-Google-Smtp-Source: ABdhPJzzGVOLSsCZzGc2tQKYoYDG4cRmDB8d7S0OVU5fOIt4+ZHhsLrQEiQukKtCUx21QtdM6mbLoYILkq4c836QRDg=
X-Received: by 2002:a9d:6e1:: with SMTP id 88mr1523664otx.10.1615359870544;
 Tue, 09 Mar 2021 23:04:30 -0800 (PST)
MIME-Version: 1.0
References: <011101781af1dc42-f0f9c581-e204-4ce8-bc82-fbc2eb047df4-000000@us-west-1.amazonses.com>
 <CAHaL5ja=c2sf7cSaF83Y8DTxuYcvgVAd0_UUVznDRTQij9jOrg@mail.gmail.com>
In-Reply-To: <CAHaL5ja=c2sf7cSaF83Y8DTxuYcvgVAd0_UUVznDRTQij9jOrg@mail.gmail.com>
From: Egenie Next <egenie.apple@gmail.com>
Date: Wed, 10 Mar 2021 12:04:19 +0500
Message-ID: <CAHaL5jZmhC7Xe2BVrhdaEqsZD--DaSEa2YhQYSK-0gUREhqBtg@mail.gmail.com>
Subject: Re: From AHR
To: notifications@automotohr.com
Content-Type: multipart/alternative; boundary="00000000000044acaf05bd294758"
X-Spam-Status: No, score=0.6
X-Spam-Score: 6
X-Spam-Bar: /
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  Replied qw Ddd Dddf Ddd Some ndnd On Wed, 10 Mar 2021 at 12:03
    PM, Egenie Next wrote: > > > On Wed, 10 Mar 2021 at 12:02 PM, Dev AutomotoHR
    < > wrote: > >> Dev AutomotoHR >> Dear Egenie Apple, >> >> devteam has sent
    you a private message. >> >> *Date:* 2021-03-09 23:02:39 >> >> *Subj [...]

 Content analysis details:   (0.6 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
  0.8 BAYES_50               BODY: Bayes spam probability is 40 to 60%
                             [score: 0.4991]
 -0.0 SPF_PASS               SPF: sender matches SPF record
  0.0 FREEMAIL_FROM          Sender email is commonly abused enduser mail
                             provider
                             [egenie.apple[at]gmail.com]
  0.0 HTML_MESSAGE           BODY: HTML included in message
  0.0 HTML_FONT_LOW_CONTRAST BODY: HTML font color similar or
                             identical to background
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
 -0.1 DKIM_VALID             Message has at least one valid DKIM or DK signature
 -0.1 DKIM_VALID_EF          Message has a valid DKIM or DK signature from
                             envelope-from domain
 -0.1 DKIM_VALID_AU          Message has a valid DKIM or DK signature from
                             author"s domain
X-Spam-Flag: NO

--00000000000044acaf05bd294758
Content-Type: text/plain; charset="UTF-8"
Content-Transfer-Encoding: quoted-printable

Replied qw
Ddd


Dddf
Ddd
Some ndnd
On Wed, 10 Mar 2021 at 12:03 PM, Egenie Next <egenie.apple@gmail.com> wrote=
:

>
>
> On Wed, 10 Mar 2021 at 12:02 PM, Dev AutomotoHR <
> notifications@automotohr.com> wrote:
>
>> Dev AutomotoHR
>> Dear Egenie Apple,
>>
>> devteam has sent you a private message.
>>
>> *Date:* 2021-03-09 23:02:39
>>
>> *Subject:* From AHR
>> ------------------------------
>>
>> please reply
>>
>>
>> =C2=A9 2021 devsupport3.automotohr.com. All Rights Reserved.
>> <http://devsupport3.automotohr.com>
>> message_id:3qUu2jvIev8BVzVOaFBQDPHjKQ5nzUfDka7muD5I8ej3NfRYUt__
>>
>

--00000000000044acaf05bd294758
Content-Type: text/html; charset="UTF-8"
Content-Transfer-Encoding: quoted-printable

<div><br></div><div dir=3D"auto">Replied qw</div><div dir=3D"auto">Ddd</div=
><div dir=3D"auto"><br></div><div dir=3D"auto"><br></div><div dir=3D"auto">=
Dddf</div><div dir=3D"auto">Ddd</div><div dir=3D"auto">Some ndnd<br><div cl=
ass=3D"gmail_quote" dir=3D"auto"><div dir=3D"ltr" class=3D"gmail_attr">On W=
ed, 10 Mar 2021 at 12:03 PM, Egenie Next &lt;<a href=3D"mailto:egenie.apple=
@gmail.com">egenie.apple@gmail.com</a>&gt; wrote:<br></div><blockquote clas=
s=3D"gmail_quote" style=3D"margin:0px 0px 0px 0.8ex;border-left-width:1px;b=
order-left-style:solid;padding-left:1ex;border-left-color:rgb(204,204,204)"=
><div><br></div><div><br><div class=3D"gmail_quote"><div dir=3D"ltr" class=
=3D"gmail_attr">On Wed, 10 Mar 2021 at 12:02 PM, Dev AutomotoHR &lt;<a href=
=3D"mailto:notifications@automotohr.com" target=3D"_blank">notifications@au=
tomotohr.com</a>&gt; wrote:<br></div><blockquote class=3D"gmail_quote" styl=
e=3D"margin:0px 0px 0px 0.8ex;border-left-width:1px;border-left-style:solid=
;padding-left:1ex;border-left-color:rgb(204,204,204)"><div style=3D"font-si=
ze:100%;line-height:1.6em;display:block;max-width:1000px;margin:0px auto;pa=
dding:0px"><div style=3D"width:100%;float:left;padding:5px 20px;text-align:=
center;box-sizing:border-box;background-color:rgb(0,0,0)"><h2 style=3D"colo=
r:rgb(255,255,255)">Dev AutomotoHR</h2></div>  <div style=3D"width:100%;flo=
at:left;padding:20px 0px"><h2 style=3D"width:100%;margin:0px 0px 20px">Dear=
 Egenie Apple,</h2><br><br>devteam has sent you a private message.<br><br><=
b>Date:</b> 2021-03-09 23:02:39<br><br><b>Subject:</b> From AHR<br><hr><p>p=
lease reply</p><br><br></div><div style=3D"width:100%;float:left;padding:20=
px 30px;box-sizing:border-box;background-color:rgb(0,0,0)"><div style=3D"fl=
oat:left;width:100%"><p><a style=3D"text-decoration:none;color:rgb(255,255,=
255)" href=3D"http://devsupport3.automotohr.com" target=3D"_blank">=C2=A9 2=
021 devsupport3.automotohr.com. All Rights Reserved.</a></p></div></div></d=
iv><div style=3D"width:100%;float:left;box-sizing:border-box;background-col=
or:rgb(0,0,0);color:rgb(0,0,0)">message_id:3qUu2jvIev8BVzVOaFBQDPHjKQ5nzUfD=
ka7muD5I8ej3NfRYUt__</div>
</blockquote></div></div>
</blockquote></div></div>

--00000000000044acaf05bd294758--';

$messageArray['gmaildesktop'] = 'From mubashir.saleemi123@gmail.com Tue Mar 09 23:24:07 2021
Received: from mail-lf1-f45.google.com ([209.85.167.45]:46841)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256
        (Exim 4.94)
        (envelope-from <mubashir.saleemi123@gmail.com>)
        id 1lJtCQ-0001ux-Eh
        for notifications@automotohr.com; Tue, 09 Mar 2021 23:24:07 -0800
Received: by mail-lf1-f45.google.com with SMTP id r3so23591162lfc.13
        for <notifications@automotohr.com>; Tue, 09 Mar 2021 23:23:46 -0800 (PST)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=gmail.com; s=20161025;
        h=mime-version:references:in-reply-to:from:date:message-id:subject:to
         :content-transfer-encoding;
        bh=PNoMrrZlrKK5d/WucoJbxV791pqiuClrqQwBH83BgLU=;
        b=oVfzlgk4J30ptdbVaIRGKGpRPcKaCBZJAYEdC8mYVphvwLK1IPTSjkTnPOB72/n6zm
         09WzVYpYWUKDXsvR3aju73WoxmXV7e0l210elqkmLzpAaXgPRGaGYl0ng9Ylix67wiWP
         7mGE3w4CLoIVcXhIH9hY3VbixUDbO5Dh15USOcaLmTLea5iCBPSmjsY+Tb7a4A7Z2CeQ
         SfxKpaMF5UE+NvcFIjZW9PrLEB4k1L8qBKiykZy80/6yE9Ls5GRF3co7PnrHcMRQo549
         yYIUlD1Bbu8T9wvZOH9yJ+ibn89xAro7cWuLuFQRMw4R4DoKvrcKxWNSlSeRDAPw6Ziu
         Hamg==
X-Google-DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=1e100.net; s=20161025;
        h=x-gm-message-state:mime-version:references:in-reply-to:from:date
         :message-id:subject:to:content-transfer-encoding;
        bh=PNoMrrZlrKK5d/WucoJbxV791pqiuClrqQwBH83BgLU=;
        b=UdNNigjh2diEBimbLY6ErJ1Bb86h2YxU2GxGHyDeDg3JX5Mu6cT2ntOX2BiG7tzDJs
         aUSw/4U/SVzLTWaGSOyctiaIeZWOzDckF0296BiuGNR0jPEN+J0QEuivPA8E7ez3Farw
         G4wtUiJslRFUH+p7vNWMwqShxax5fHgUPD9xRvpMHAej9lvF3EaEE7ttYzqp9ya561dg
         AVsLwMGQwWsi72i7hqsDOc9pD42rjSI20pla1mdffXbUjTiNa1vU1KfC5nSQAUFJiYR2
         AZVIEnIc1BTsO/LcrDhwfo6yfyt5NxTifqK9Nxkafa9xZ88Y8y8bgU0/WWaN1tLvjVwp
         /p0g==
X-Gm-Message-State: AOAM530A0lBljS39e21ZCqEEUuN1oFnVSK1exFmrcjjHEfx+YrQ4ktoF
        Nb2n7xu5yzm7poebWWLYDl3g6ZwqNaWDSsF6NzAeHVlI
X-Google-Smtp-Source: ABdhPJzNeEbNCZYRjcp8g/v8KcVDpud+yrAP6nPVmJOgsspQaHElG0IzmdzeNlTPF7RhrYdFSFalyHiCnhw/FrhCZQ0=
X-Received: by 2002:a05:6512:114f:: with SMTP id m15mr1258876lfg.152.1615361003648;
 Tue, 09 Mar 2021 23:23:23 -0800 (PST)
MIME-Version: 1.0
References: <011101781840e4c0-b058edb4-465e-4d2c-8b58-a1e2597746c0-000000@us-west-1.amazonses.com>
In-Reply-To: <011101781840e4c0-b058edb4-465e-4d2c-8b58-a1e2597746c0-000000@us-west-1.amazonses.com>
From: Mubashir Ahmed <mubashir.saleemi123@gmail.com>
Date: Tue, 9 Mar 2021 23:23:10 -0800
Message-ID: <CAHvz9cODpmvcDDE0A8ovn24r2thfx2iByLu6f9icMfTY0BghWA@mail.gmail.com>
Subject: Re: s
To: notifications@automotohr.com
Content-Type: text/plain; charset="UTF-8"
Content-Transfer-Encoding: quoted-printable
X-Spam-Status: No, score=0.8
X-Spam-Score: 8
X-Spam-Bar: /
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  hello team, reply sent from gmail desktop client hope it works!!!!

 Content analysis details:   (0.8 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
  0.8 BAYES_50               BODY: Bayes spam probability is 40 to 60%
                             [score: 0.5000]
 -0.0 SPF_PASS               SPF: sender matches SPF record
  0.0 FREEMAIL_FROM          Sender email is commonly abused enduser mail
                             provider
                             [mubashir.saleemi123[at]gmail.com]
  0.2 FREEMAIL_ENVFROM_END_DIGIT Envelope-from freemail username ends
                             in digit
                             [mubashir.saleemi123[at]gmail.com]
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
 -0.1 DKIM_VALID             Message has at least one valid DKIM or DK signature
 -0.1 DKIM_VALID_EF          Message has a valid DKIM or DK signature from
                             envelope-from domain
 -0.1 DKIM_VALID_AU          Message has a valid DKIM or DK signature from
                             author"s domain
X-Spam-Flag: NO

hello team,

reply sent from gmail desktop client

hope it works!!!!

On Tue, Mar 9, 2021 at 10:30 AM Dev AutomotoHR
<notifications@automotohr.com> wrote:
>
> Dev AutomotoHR
>
> Dear Mubashir Ahmed,
>
>
> Development Team has sent you a private message.
>
> Date: Mar 09 2021, Tue 11:30:08
>
> Subject: s
> ________________________________
>
> ghet the email
>
>
>
> =C2=A9 2021 devsupport3.automotohr.com. All Rights Reserved.
>
> message_id:vAkSR28erj9QKi0c9j5uArfbiS0Y81fEBAwtCFI3YRUIaUVj__';

$messageArray['outlook_client'] = 'From mubashar.ahmed@egenienext.com Wed Mar 10 01:11:15 2021
Received: from mx1.egenienext.com ([34.194.249.63]:51300)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384
        (Exim 4.94)
        (envelope-from <mubashar.ahmed@egenienext.com>)
        id 1lJus6-000682-IM
        for notifications@automotohr.com; Wed, 10 Mar 2021 01:11:15 -0800
Received: from mx1.egenienext.com (mx1.egenienext.com [127.0.0.1])
        by mx1.egenienext.com (Postfix) with ESMTP id BE7E0C10B1
        for <notifications@automotohr.com>; Wed, 10 Mar 2021 09:10:33 +0000 (UTC)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/simple; d=egenienext.com;
        s=mail; t=1615367433;
        bh=/S0OvMqOH/PKW1rp+RA5UnfvWaeo58jm6sWpXxAPL00=;
        h=From:To:References:In-Reply-To:Subject:Date:From;
        b=lVA5hEGqxfYCIuCS8gAsnN1Z2X/AQBjpLR9pYG+P8QFcYieETgEUtuADNPjKxBkSO
         bYFUDAb7rUGfEzs7rSrNTd2Z+MZ5qf9UOmP0TB/ysEENq6yoowppH9iecN4azgzj4e
         QZnKEY6YMNkuBMJaG3VvSVOaOzwNHhMF2QqbDOnY=
Authentication-Results: mx1.egenienext.com (amavisd-new);
        dkim=fail (1024-bit key) reason="fail (bad RSA signature)"
        header.d=egenienext.com
Received: from mx1.egenienext.com ([127.0.0.1])
        by mx1.egenienext.com (mx1.egenienext.com [127.0.0.1]) (amavisd-new, port 10024)
        with ESMTP id 86AeqH2O9Fut for <notifications@automotohr.com>;
        Wed, 10 Mar 2021 09:10:33 +0000 (UTC)
Received: from DESKTOPVM9EE2C (unknown [72.255.38.246])
        by mx1.egenienext.com (Postfix) with ESMTPA id 90A11C106A
        for <notifications@automotohr.com>; Wed, 10 Mar 2021 09:10:32 +0000 (UTC)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/simple; d=egenienext.com;
        s=mail; t=1615367433;
        bh=/S0OvMqOH/PKW1rp+RA5UnfvWaeo58jm6sWpXxAPL00=;
        h=From:To:References:In-Reply-To:Subject:Date:From;
        b=lVA5hEGqxfYCIuCS8gAsnN1Z2X/AQBjpLR9pYG+P8QFcYieETgEUtuADNPjKxBkSO
         bYFUDAb7rUGfEzs7rSrNTd2Z+MZ5qf9UOmP0TB/ysEENq6yoowppH9iecN4azgzj4e
         QZnKEY6YMNkuBMJaG3VvSVOaOzwNHhMF2QqbDOnY=
From: "Mubashir Ahmed" <mubashar.ahmed@egenienext.com>
To: <notifications@automotohr.com>
References: <011101781b56688a-3feea8fe-3c57-4248-917c-fe4fc11d59e8-000000@us-west-1.amazonses.com>
In-Reply-To: <011101781b56688a-3feea8fe-3c57-4248-917c-fe4fc11d59e8-000000@us-west-1.amazonses.com>
Subject: RE: As of 03/10/2021 - AHR
Date: Wed, 10 Mar 2021 01:10:29 -0800
Message-ID: <004f01d7158d$38c38a30$aa4a9e90$@egenienext.com>
MIME-Version: 1.0
Content-Type: multipart/alternative;
        boundary="----=_NextPart_000_0050_01D7154A.2AA10D80"
X-Mailer: Microsoft Outlook 16.0
Thread-Index: AQGgVMdDtu/RqN3PrUT0OjA02NzvB6rqcMuw
Content-Language: en-us
X-Spam-Status: No, score=1.0
X-Spam-Score: 10
X-Spam-Bar: +
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  Hey replying from outlook client Testing 123 As
 Content analysis details:   (1.0 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
  0.8 BAYES_50               BODY: Bayes spam probability is 40 to 60%
                             [score: 0.5000]
 -0.0 SPF_PASS               SPF: sender matches SPF record
  0.0 HTML_FONT_LOW_CONTRAST BODY: HTML font color similar or
                             identical to background
  0.0 HTML_MESSAGE           BODY: HTML included in message
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
  0.1 DKIM_INVALID           DKIM or DK signature exists, but is not valid
X-Spam-Flag: NO

This is a multipart message in MIME format.

------=_NextPart_000_0050_01D7154A.2AA10D80
Content-Type: text/plain;
        charset="UTF-8"
Content-Transfer-Encoding: quoted-printable

Hey replying from outlook client

=20

Testing 123

As

D

Asd

Asd

As

d

=20

Regards,

Mubashir Ahmed

=20

From: Dev AutomotoHR <notifications@automotohr.com>=20
Sent: Wednesday, March 10, 2021 12:53 AM
To: mubashar.ahmed@egenienext.com
Subject: As of 03/10/2021 - AHR

=20


Dev AutomotoHR


Dear Development Team,




devteam has sent you a private message.

Date: 2021-03-10 00:52:24

Subject: As of 03/10/2021 - AHR

  _____ =20

Please, reply to this message.

=20

 <http://devsupport3.automotohr.com> =C2=A9 2021 =
devsupport3.automotohr.com. All Rights Reserved.

message_id:KelG4imMrkT86RxZCOgCep138WDJC1qmgM2l5p7wJ0EQSbPu06__


------=_NextPart_000_0050_01D7154A.2AA10D80
Content-Type: text/html;
        charset="UTF-8"
Content-Transfer-Encoding: quoted-printable

<html xmlns:v=3D"urn:schemas-microsoft-com:vml" =
xmlns:o=3D"urn:schemas-microsoft-com:office:office" =
xmlns:w=3D"urn:schemas-microsoft-com:office:word" =
xmlns:m=3D"http://schemas.microsoft.com/office/2004/12/omml" =
xmlns=3D"http://www.w3.org/TR/REC-html40"><head><meta =
http-equiv=3DContent-Type content=3D"text/html; charset=3Dutf-8"><meta =
name=3DGenerator content=3D"Microsoft Word 15 (filtered medium)"><!--[if =
!mso]><style>v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
w\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style><![endif]--><style><!--
/* Font Definitions */
@font-face
        {font-family:"Cambria Math";
        panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
        {font-family:Calibri;
        panose-1:2 15 5 2 2 2 4 3 2 4;}
/* Style Definitions */
p.MsoNormal, li.MsoNormal, div.MsoNormal
        {margin:0in;
        font-size:11.0pt;
        font-family:"Calibri",sans-serif;}
h2
        {mso-style-priority:9;
        mso-style-link:"Heading 2 Char";
        mso-margin-top-alt:auto;
        margin-right:0in;
        mso-margin-bottom-alt:auto;
        margin-left:0in;
        font-size:18.0pt;
        font-family:"Calibri",sans-serif;
        font-weight:bold;}
a:link, span.MsoHyperlink
        {mso-style-priority:99;
        color:blue;
        text-decoration:underline;}
span.Heading2Char
        {mso-style-name:"Heading 2 Char";
        mso-style-priority:9;
        mso-style-link:"Heading 2";
        font-family:"Calibri Light",sans-serif;
        color:#2F5496;}
span.EmailStyle20
        {mso-style-type:personal-reply;
        font-family:"Calibri",sans-serif;
        color:windowtext;}
.MsoChpDefault
        {mso-style-type:export-only;
        font-family:"Calibri",sans-serif;}
@page WordSection1
        {size:8.5in 11.0in;
        margin:1.0in 1.0in 1.0in 1.0in;}
div.WordSection1
        {page:WordSection1;}
--></style><!--[if gte mso 9]><xml>
<o:shapedefaults v:ext=3D"edit" spidmax=3D"1026" />
</xml><![endif]--><!--[if gte mso 9]><xml>
<o:shapelayout v:ext=3D"edit">
<o:idmap v:ext=3D"edit" data=3D"1" />
</o:shapelayout></xml><![endif]--></head><body lang=3DEN-US link=3Dblue =
vlink=3Dpurple style=3D"word-wrap:break-word"><div =
class=3DWordSection1><p class=3DMsoNormal>Hey replying from outlook =
client<o:p></o:p></p><p class=3DMsoNormal><o:p>&nbsp;</o:p></p><p =
class=3DMsoNormal>Testing 123<o:p></o:p></p><p =
class=3DMsoNormal>As<o:p></o:p></p><p =
class=3DMsoNormal>D<o:p></o:p></p><p =
class=3DMsoNormal>Asd<o:p></o:p></p><p =
class=3DMsoNormal>Asd<o:p></o:p></p><p =
class=3DMsoNormal>As<o:p></o:p></p><p =
class=3DMsoNormal>d<o:p></o:p></p><p =
class=3DMsoNormal><o:p>&nbsp;</o:p></p><p =
class=3DMsoNormal>Regards,<o:p></o:p></p><p class=3DMsoNormal>Mubashir =
Ahmed<o:p></o:p></p><p class=3DMsoNormal><o:p>&nbsp;</o:p></p><div =
style=3D"border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0in =
0in 0in"><p class=3DMsoNormal><b>From:</b> Dev AutomotoHR =
&lt;notifications@automotohr.com&gt; <br><b>Sent:</b> Wednesday, March =
10, 2021 12:53 AM<br><b>To:</b> =
mubashar.ahmed@egenienext.com<br><b>Subject:</b> As of 03/10/2021 - =
AHR<o:p></o:p></p></div><p =
class=3DMsoNormal><o:p>&nbsp;</o:p></p><div><h2 align=3Dcenter =
style=3D"text-align:center;line-height:19.2pt;background:black"><span =
style=3D"color:white">Dev AutomotoHR<o:p></o:p></span></h2><div><h2 =
style=3D"mso-margin-top-alt:0in;margin-right:0in;margin-bottom:15.0pt;mar=
gin-left:0in;line-height:19.2pt">Dear Development =
Team,<o:p></o:p></h2><p class=3DMsoNormal =
style=3D"line-height:19.2pt"><br><br>devteam has sent you a private =
message.<br><br><b>Date:</b> 2021-03-10 00:52:24<br><br><b>Subject:</b> =
As of 03/10/2021 - AHR<o:p></o:p></p><div class=3DMsoNormal =
align=3Dcenter style=3D"text-align:center;line-height:19.2pt"><hr =
size=3D2 width=3D"100%" align=3Dcenter></div><p =
style=3D"line-height:19.2pt">Please, reply to this =
message.<o:p></o:p></p><p class=3DMsoNormal =
style=3D"margin-bottom:12.0pt;line-height:19.2pt"><o:p>&nbsp;</o:p></p></=
div><div><div><p style=3D"line-height:19.2pt;background:black"><span =
style=3D"color:white"><a =
href=3D"http://devsupport3.automotohr.com"><span =
style=3D"color:white;text-decoration:none">=C2=A9 2021 =
devsupport3.automotohr.com. All Rights =
Reserved.</span></a></span><o:p></o:p></p></div></div></div><div><p =
class=3DMsoNormal style=3D"background:black"><span =
style=3D"color:black">message_id:KelG4imMrkT86RxZCOgCep138WDJC1qmgM2l5p7w=
J0EQSbPu06__<o:p></o:p></span></p></div></div></body></html>
------=_NextPart_000_0050_01D7154A.2AA10D80--';
$email = $messageArray['gmaildesktop'] = 'From mubashir.saleemi123@gmail.com Wed Mar 10 02:48:31 2021
Received: from mail-lf1-f48.google.com ([209.85.167.48]:35549)
        by smg222.automotohr.com with esmtps  (TLS1.2) tls TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256
        (Exim 4.94)
        (envelope-from <mubashir.saleemi123@gmail.com>)
        id 1lJwOE-0001KC-1O
        for notifications@automotohr.com; Wed, 10 Mar 2021 02:48:31 -0800
Received: by mail-lf1-f48.google.com with SMTP id e7so32713739lft.2
        for <notifications@automotohr.com>; Wed, 10 Mar 2021 02:48:09 -0800 (PST)
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=gmail.com; s=20161025;
        h=mime-version:references:in-reply-to:from:date:message-id:subject:to
         :content-transfer-encoding;
        bh=qkVRQxuI3HA0KZ9oKl8W/uF1Z1sK1xiyycB1DtbvV3I=;
        b=RY6iZy9JVzlresaehSdElK1yiIF7y0eiEszeoiuw9mkof8H6pjET7z08hyPtDVUV1a
         pa+WqkgYu+/QMdPYmR6Jsf6YsRbYn5M9/jIHv8rvC489VR7S4GJbLYrIIWr3pW2kk3LK
         zO5OPcXAXaKv43Krey0PCm+lq4AhtKsXxCh251yq21Bd0oohVyLLYO9KpOwL3HObm5UR
         9WxOydNUdy3oQfQolfWnZHXaMvOr2JOk0p1Jy9RpxeWh9kVk8WsrOr0x8MmcTvJiktjt
         igg7bFhDF9Llm2CJesGMzxrDNETWfoEMeXWTLx6vEhxtAM6Y8a37hlaua0KTgjTrB9sm
         QBqQ==
X-Google-DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
        d=1e100.net; s=20161025;
        h=x-gm-message-state:mime-version:references:in-reply-to:from:date
         :message-id:subject:to:content-transfer-encoding;
        bh=qkVRQxuI3HA0KZ9oKl8W/uF1Z1sK1xiyycB1DtbvV3I=;
        b=jCw6ZQKTTB2xaiDVJ0cHq6L5YbRSrdNtfLmo7sPigCdBpXgiot/DUYO/AfK4oCNcg9
         IRahoo0y7FDtMj/VCZL7/WlPd2W3j6kLPfa8yWk0I8KNVV0JPE8uv2fuCmhxs4ox/T2m
         15HRZ3eIEw47Iam3nqeAqPbqNVD0UKsrhJSD5gY6jKt6wV64x6PhNdWOnWlVOq67sULy
         erlHjAISHnMN7d88HjhR/Kr9AMtfpbpoPEePm6Qv2C8czm/3W+v95d9mkk7cuvpUMWPY
         DLWnroT1ed/NpoW1aW/nrHapYEobHoK4C1Z7RjM5vBQE5vhUTeHCfZ5SJbhqRfnYSMFb
         7jJg==
X-Gm-Message-State: AOAM530mtgVG4faJbEOJ/XQhfpnJ0MfNiOU/6zOCBt5Ly3p+ZbEDuGO/
        tl4S0YCmFNGQu6Y+C6sKSsCj8qn8AdQnt8os9lKH/9LS
X-Google-Smtp-Source: ABdhPJyBN/vFGeu9jQmQoEANcTx4iBLcD6kXhmQOdUYbQpCxHcyYvM91yjPMH2QDM1vpRhWjqmJ1trafl7PRLFwoQi0=
X-Received: by 2002:a05:6512:114f:: with SMTP id m15mr1691092lfg.152.1615373268008;
 Wed, 10 Mar 2021 02:47:48 -0800 (PST)
MIME-Version: 1.0
References: <011101781b924554-02112b26-5c4c-49f2-8da1-e77acaf04545-000000@us-west-1.amazonses.com>
In-Reply-To: <011101781b924554-02112b26-5c4c-49f2-8da1-e77acaf04545-000000@us-west-1.amazonses.com>
From: Mubashir Ahmed <mubashir.saleemi123@gmail.com>
Date: Wed, 10 Mar 2021 02:47:33 -0800
Message-ID: <CAHvz9cOVvG7XJ3oAB=sEN0cFoa8-vgPqQcO3sJthXC88gGb_rQ@mail.gmail.com>
Subject: Re: reply
To: notifications@automotohr.com
Content-Type: text/plain; charset="UTF-8"
Content-Transfer-Encoding: quoted-printable
X-Spam-Status: No, score=0.8
X-Spam-Score: 8
X-Spam-Bar: /
X-Ham-Report: Spam detection software, running on the system "smg222.automotohr.com",
 has NOT identified this incoming email as spam.  The original
 message has been attached to this so you can view it or label
 similar future email.  If you have any questions, see
 root\@localhost for details.
 Content preview:  Replying part 2 sd as da sd as d asdasdasdas On Wed, Mar 10,
    2021 at 1:57 AM Dev AutomotoHR wrote: > > Dev AutomotoHR > > Dear Mubashir
    Ahmed, > > > Development Team has sent you a private message. > > Date: Mar
    10 2021, Wed 02:57:53 > > Subje [...]
 Content analysis details:   (0.8 points, 5.0 required)
  pts rule name              description
 ---- ---------------------- --------------------------------------------------
  0.8 BAYES_50               BODY: Bayes spam probability is 40 to 60%
                             [score: 0.5000]
  0.0 FREEMAIL_FROM          Sender email is commonly abused enduser mail
                             provider
                             [mubashir.saleemi123[at]gmail.com]
  0.2 FREEMAIL_ENVFROM_END_DIGIT Envelope-from freemail username ends
                             in digit
                             [mubashir.saleemi123[at]gmail.com]
 -0.0 SPF_PASS               SPF: sender matches SPF record
 -0.1 DKIM_VALID_AU          Message has a valid DKIM or DK signature from
                             author"s domain
  0.1 DKIM_SIGNED            Message has a DKIM or DK signature, not necessarily
                             valid
 -0.1 DKIM_VALID_EF          Message has a valid DKIM or DK signature from
                             envelope-from domain
 -0.1 DKIM_VALID             Message has at least one valid DKIM or DK signature
X-Spam-Flag: NO

Replying part 2
sd
as
da
sd
as
d
asdasdasdas

On Wed, Mar 10, 2021 at 1:57 AM Dev AutomotoHR
<notifications@automotohr.com> wrote:
>
> Dev AutomotoHR
>
> Dear Mubashir Ahmed,
>
>
> Development Team has sent you a private message.
>
> Date: Mar 10 2021, Wed 02:57:53
>
> Subject: reply
> ________________________________
>
> rely to this
>
>
>
> =C2=A9 2021 devsupport3.automotohr.com. All Rights Reserved.
>
> message_id:fws8U0Swd99Dc22glNrA72rW8nmMTFA8c3g648DhiMUvPXLa__';
fclose($fd);
$email = $messageArray['gmaildesktop'];
// $email = $messageArray['gmail'];
// $email = $messageArray['outlook'];
// $email = $messageArray['iphonegmail'];
// $email = $messageArray['mail'];
// $email = $messageArray['outlook_client'];
$email = str_replace("'", '"', $email);
// mail($devEmail, 'PipeScript: ' . date('Y-m-d H:i:s'), $email);
// $hostname = "172.31.18.37";
// $username = "ahrdbadmin";
// $password = '8E*QrG)M5nw6g';
// $dbhandle = mysqli_connect($hostname, $username, $password, 'automoto_hr') or die('Unable to Connect to MySql');
// $currentDate = date('Y-m-d H:i:s');
// $query_string = "insert into incoming_mails (`full_info`,`date_received`) VALUES ('" . $email . "','" . $currentDate . "')";
// $result = mysqli_query($dbhandle, $query_string);
require_once('PlancakeEmailParser.php');
$emailData = $email;
$emailParser = new PlancakeEmailParser($emailData);

echo '<pre>';

function getActualBody($emailData){
    $headers = iconv_mime_decode_headers($emailData, 0, "ISO-8859-1");
    // Get incoming types
    $content_types = explode('Content-Type:', $emailData);
    // Set default body
    $emailBody = '';
    // Loop and get body
    foreach($content_types as $type) if(preg_match('/text\/plain/i', $type)) $emailBody = $type;
    // If body not found get the body 
    // returned from library
    if(empty($emailBody)) $emailBody = $emailParser->getPlainBody();
    //
    if(preg_match('/Content-Transfer-Encoding: quoted-printable/i', $emailBody))
    $emailBody = quoted_printable_decode($emailBody);
    //
    end ($headers);
    $key = key($headers);
    $kv = $key.': '.$headers[$key];
    // Remove all headers from body
    if(preg_match('/'.($key).'/i', $emailBody)){
        $emailBody = substr($emailBody, strpos($emailBody, $kv)+  strlen($kv));
        // $emailBody = preg_replace('/([^:]+):\s+?(.*)$/m', '', $emailBody);
    }
    //
    $emailBody = preg_replace('/Content-Transfer-Encoding: quoted-printable/i', '', $emailBody);
    $emailBody = preg_replace('/charset=(.*)/i', '', $emailBody);
    // Convert string to array to eliminate
    // extra lines
    $lines = preg_split("/(\r?\n|\r)/", $emailBody);
    // Set default reply
    $reply = '';
    // Loop and set the reply
    foreach($lines as $line){
        // Eliminate the type
        if(preg_match('/text\/plain/i', $line)) continue;
        if(preg_match('/message..\s+On/i', $line)) continue;
        // If old reply is attached then remove it
        if(
            !preg_match('/On\s[a-zA-Z]{3}/i', $line) &&
            !preg_match('/(>)\s+On/i', $line) &&
            !preg_match('/>\s+/i', $line) &&
            !preg_match('/________________________________/i', $line)  &&
            !preg_match('/From:\s[a-zA-Z]/i', $line)
        ) {
            $reply .= strip_tags($line)."\n";
        } else return trim(strip_tags($reply));
    }
    //
    return trim(strip_tags($reply));
}    

echo (getActualBody($emailData));


die;


if (strpos($emailData, 'message_id:')) {
    $secret_key = substr($emailData, strpos($emailData, 'message_id:') + 11, 50);
    $secret_key = trim(preg_replace('/\s+/', '', $secret_key));
    $secret_key = str_replace('=', "", $secret_key);
    $secret_key = str_replace(' ', '', $secret_key);
    $secret_key = str_replace('_', '', $secret_key);
    $query_string = "select * from private_message where identity_key = '$secret_key' and outbox = 1";
    $result = mysqli_query($dbhandle, $query_string);
    $messageData = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) > 0) {
        $from = ($emailFrom = $emailParser->getFrom());
        $to = $emailParser->getTo();
        $to = $to[0];
        $from = $emailParser->extract_email_address($from[0]);
        $subject = $emailParser->getSubject();
        $body = $emailParser->getHTMLBody();
        $attachment_name = NULL;
        $bodyEnd = strpos($body, 'notifications@applybuz.com');
        $range = substr($body, 0, $bodyEnd - 11);
        $condition_executed = '';

        if (strpos($body, '<div class="gmail_extra">')) {//solution for Gmail
            $bodyEnd = strpos($body, '<div class="gmail_extra">') + 25;
            $newBody = strip_tags(substr($body, 0, $bodyEnd), "<br>");
            $condition_executed = 'Primary IF Condition Lno: 69';
        } elseif (strpos($body, 'wrote:')) {//solution for yahoo
            $newBody = "";
            $body_array = explode("<br>", $range);
            
            if(!empty($body_array)) {
                for ($i = 0; $i < count($body_array) - 1; $i++) {
                    $newBody .= strip_tags($body_array[$i], "<br>");
                }
            } else {
                $strposition = strrpos($range, '<blockquote');

                if($strposition) {
                    $newBody = substr($range, 0, $strposition);
                }
            }
            
            $strposition_virus = strrpos($newBody, '<https://www.avast.com/sig-email?');

            if($strposition_virus) {
                $newBody = substr($newBody, 0, $strposition_virus);
            }
            
            $strposition_virus1 = strrpos($newBody, 'Virus-free.');

            if($strposition_virus) {
                $newBody = substr($newBody, 0, $strposition_virus1);
            }

            $condition_executed = 'Else IF Condition Lno: 73';
        } elseif (strpos($body, '<hr id="stopSpelling">')) {//solution for Hotmail
            $range = strpos($body, '<hr id="stopSpelling">');
            $newBody = strip_tags(substr($body, 0, $range), "<br>");
            $condition_executed = 'Else IF Condition Lno: 90';
        } else { //general solution, If all fails
            $newBody = strip_tags(substr($body, 0, $bodyEnd), "<p>");
            $explodedArray = explode('From:', $newBody);
            $newBody = $explodedArray[0];
            $condition_executed = 'Else IF Condition Lno: 94';
        }

        $getbody = $emailParser->getBody();           
        $getplainbody = $emailParser->getPlainBody();

        if($newBody == '' || $newBody == NULL) {
            $condition_executed = 'I am inside 104';
            $strposition = strrpos($range, '<blockquote');

            if($strposition) {
                $condition_executed = 'I am inside 108';
                $newBody = substr($range, 0, $strposition);
            } else {
                $condition_executed = 'I am inside 112';
                $bodyendpoint = strpos($getbody, 'notifications@applybuz.com');
                $newBody = substr($getbody, 0, $bodyendpoint);
                $strposition = strrpos($newBody, 'On');
                
                if($strposition) {
                    $newBody = substr($newBody, 0, $strposition - 2);
                }
            } 
            
            $strposition_virus1 = strrpos($newBody, 'Virus-free.');

            if($strposition_virus) {
                $newBody = substr($newBody, 0, $strposition_virus1);
            }
            
        }

    $email_parser_query = "insert into email_parser_tracking (getbody,getplainbody,gethtmlbody,newbody,secret_key,condition_executed,range_var,messageData) VALUES ('" . $getbody . "','" . $getplainbody . "','" . $body . "','" . $newBody . "','" . $secret_key . "','" . $condition_executed . "','" . $range . "','" . $messageData . "')";
    mysqli_query($dbhandle, $email_parser_query);
    
    $strposition_virus2 = strrpos($newBody, 'Virus-free.');
    
    if($strposition_virus) {
        $newBody = substr($newBody, 0, $strposition_virus2);
    }
        
    /* Check for attachments in the email and attach it to PM --- START --- */
    $headers = iconv_mime_decode_headers($emailData, 0, "ISO-8859-1");
    $headers = array_combine(array_map("strtolower", array_keys($headers)), array_values($headers));
    preg_match('/boundary=(.*)/', $headers['content-type'], $match);
    $boundary = trim($match[1], '"');
    $content_types = explode('Content-Type:', $emailData);
    $has_attachments = array();
    require_once('/home/automotohr/public_html/application/libraries/aws/aws.php');
    // To get the latest attachments
    $content_types = array_reverse($content_types);
    
    foreach ($content_types as $k => $v) {
        $content_type_parts = explode('name=', $v);
        $type = array();
        if(sizeof($content_type_parts) > 1) {
            $size = sizeof($content_type_parts);
            $getname = str_replace('"', '', $content_type_parts[1]);
            $name = trim(explode('Content-Disposition:', $getname)[0]);
            // For yahoo client
            if(preg_match('/yahoo|ymail/i', htmlentities($headers['message-id']))){
              $type = trim(preg_split('/\n/', $content_type_parts[0])[0]);
              $getname = iconv_mime_decode($getname, 0, "UTF-8" );
              $content = explode('Content-ID:', $content_type_parts[$size-1])[1];
              $name = trim($getname);
            } else if(preg_match('/(outlook|hotmail|gmail)/i', htmlentities($headers['message-id']))){
              // For outlook, gmail
              $type = trim(explode(';', $content_type_parts[0])[0]);
              $content = explode('base64', $content_type_parts[$size-1])[1];
            } else {
              $type = trim(explode(';', $content_type_parts[0])[0]);
              // Reset the name for ease
              $name = $getname = trim(ltrim(explode('|',preg_replace('/("\s+)/i', '|',  $content_type_parts[1]))[0], '"'));
              $content = "\n\n".trim(preg_split('/(\n\n)/', $content_type_parts[2])[1]);
            }

            $attachment_id_array = explode('X-Attachment-Id:', $content);
            // Check if there an attachment id set // Fix for outlook
            if(isset($attachment_id_array[1])){
              $attachment_id = $attachment_id_array[1];
              $attachment = preg_split('/(\n\n)/', $attachment_id)[1];
              $remove_boundary = explode('--'.$boundary, $attachment)[0];
            } else {
              $attachment = preg_split('/(\n\n)/', $attachment_id_array[0])[1];
              $remove_boundary = explode('--'.$boundary, $attachment)[0];
            }

            $name_array = preg_split('/\n+/', $name);
            $name = trim($name_array[0]);
            $has_attachments[] = array('size' =>$size, 'type'=>trim($type), 'name'=>$name, 'attachment'=>$remove_boundary);

            if (!empty($remove_boundary)) { //Store attachment and upload on AWS
                $attachment_decoded = base64_decode($remove_boundary);
                $filePath = "/home/automotohr/public_html/assets/mail_files/"; //making Directory to store

                if (isset($name)) {
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777);
                    }
                
                    $attachment_name = rand().'_'.$name;
                    $save_attachment = fopen($filePath . $attachment_name, 'w'); //Write data back to pdf file
                    fwrite($save_attachment, $attachment_decoded);
                    fclose($save_attachment); //close output file

                    $aws = new AwsSdk();
                    $aws->putToBucket($attachment_name, $filePath . $attachment_name, 'automotohrattachments'); //uploading file to AWS
                    unlink('/home/automotohr/public_html/assets/mail_files/' . $attachment_name);
                }
            }
        }
    }
    /* Check for attachments in the email and attach it to PM ---  END  --- */
        //saving message in private message table
        $jobId = $messageData['job_id'];
        
        if (isset($messageData['job_id']) && !empty($messageData['job_id']) && $messageData['job_id'] != "") {
            $query_string = "insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,job_id, attachment) VALUES ('" . $messageData['to_id'] . "','" . $messageData['from_id'] . "','" . $messageData['to_type'] . "','" . $messageData['from_type'] . "','" . $currentDate . "','" . $subject . "','" . $newBody . "',$jobId,'" . $attachment_name . "')";
            mysqli_query($dbhandle, $query_string);
            $query_string = "insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,users_type, attachment) VALUES ('" . $messageData['to_id'] . "','" . $messageData['from_id'] . "','" . $messageData['to_type'] . "','" . $messageData['from_type'] . "','" . $currentDate . "','" . $subject . "','" . $newBody . "','employee','" . $attachment_name . "')";
            mysqli_query($dbhandle, $query_string);
        } else {
            $query_string = "insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,users_type, attachment) VALUES ('" . $messageData['to_id'] . "','" . $messageData['from_id'] . "','" . $messageData['to_type'] . "','" . $messageData['from_type'] . "','" . $currentDate . "','" . $subject . "','" . $newBody . "','employee','" . $attachment_name . "')";
            mysqli_query($dbhandle, $query_string);
        }
    }
}

/* Saves the data into a file */
$fdw = fopen("/home/automotohr/public_html/assets/mails/pipemail.txt", "w+");
fwrite($fdw, $email);
fclose($fdw);
///* Script End */