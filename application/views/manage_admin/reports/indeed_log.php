<div class="container">

    <div class="panel panel-success">
        <div class="panel-heading">
            Request
        </div>
        <div class="panel-body">
            <pre>
                <code>
                    <?= json_encode(
                        json_decode($record["request_json"]),
                        JSON_PRETTY_PRINT
                    ); ?>
                </code>
            </pre>
        </div>
    </div>

    <div class="panel panel-success">
        <div class="panel-heading">
            Response Code
        </div>
        <div class="panel-body">
            <pre>
                <code>
                    <?= $record["response_code"]; ?>
                </code>
            </pre>
        </div>
    </div>


    <div class="panel panel-success">
        <div class="panel-heading">
            Response
        </div>
        <div class="panel-body">
            <pre>
                <code>
                    <?= json_encode(
                        json_decode($record["response_json"]),
                        JSON_PRETTY_PRINT
                    ); ?>
                </code>
            </pre>
        </div>
    </div>


    <div class="panel panel-success">
        <div class="panel-heading">
            Errors
        </div>
        <div class="panel-body">
            <pre>
                <code>
                    <?= $record["response_errors"]; ?>
                </code>
            </pre>
        </div>
    </div>


</div>