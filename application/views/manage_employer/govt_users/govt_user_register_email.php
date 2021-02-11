<table border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td>
                <p>Dear <b><?=  ucwords($username) ?></b>,</p>
                <p>Please, use the below credentials to access <b>{{company_name}}</b>.</p>
                <p><b>Username</b> : <?= $username  ?></p>
                <p><b>Password</b> : <?= $password  ?></p>
                <p>Click on the below link to access the panel.</p>
                <br />
                <p>
                    <a href="<?=base_url('govt_login/'.$encrypted_company_id);?>" style="background-color: #81b431; color: #ffffff; padding: 5px 10px; font-size: 16px; border-radius: 5px;">Login</a>
                </p>
            </td>
        </tr>
    </tbody>
</table>