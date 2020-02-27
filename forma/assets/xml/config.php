<?php if(basename(__file__) == 'config.php') exit; ?>
<?xml version="1.0" encoding="utf-8"?>
<xml>
    <Addresses>
        <!-- Enter your e-mail address -->
        <address>support@supreme-garden.ru</address>
        <address on="subject" value="Support"></address>
        <address on="subject" value="Sales"></address>
        <address on="subject" value="Other"></address>
    </Addresses>
    <Config>
        <smtp>
        <!-- smtp gmail config -->
            <use>no</use>
            <auth>yes</auth>
            <secure>tls</secure>
            <host>smtp.supreme-garden.ru</host>
            <username>support@supreme-garden.ru</username>
            <password>R8c0A9z6</password>
            <port>25</port>
        </smtp>
        <charset>utf-8</charset>
    </Config>
</xml>
