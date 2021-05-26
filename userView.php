<?php
session_start();

//getting id of the logged in user
$user_id = $_SESSION['userId'];
//getting the ticket id when details button is clicked 
$ticket_id = $_SESSION['ticketId'];
//getting employee is
$employee_id = $_SESSION['employeeId'];

//load XML file with ticket info
$XMLhelpdesk = simplexml_load_file("xml/helpdesk.xml");

//empty array to create ticket detail
$ticket_details = array();

//loop through file to get ticket detail 
for($i = 0; $i < sizeof($XMLhelpdesk); $i++){
    //if block to ensure SESSION ticket id = xml ticket id and SESSION user id = client id 
    if ($XMLhelpdesk->ticket[$i]['ticketid'] == $ticket_id){
        //add info to empty array 
        array_push($ticket_details, $XMLhelpdesk->ticket[$i]);

    }
}

//loop through file to get ticket comments 
for($i = 0; $i < sizeof($XMLhelpdesk); $i++){
    //if block to ensure SESSION ticket id = xml ticket id
    if ($XMLhelpdesk->ticket[$i]['ticketid'] == $ticket_id){
        //add info to empty array 
        $ticket_comments = $XMLhelpdesk->ticket[$i]->comment;

    }
}


//ADDING A MESSAGE
//loop through ticket details 
for($i = 0; $i < sizeof($ticket_details); $i++){
    //adding messages only to comments
    if(isset($_POST['leaveComment'])){
        if (isset($_POST['messgaeBox']) !== ''){

            //timestamp
            $t_tstamp = date("Y-m-d H:i:s", time());
            //var that hold the message
            $sendMsg = $_POST['messageBox'];

            //comment is a child element of ticket
            $comment_tag = $ticket_details[$i]->addChild('comment');
            $comment_tag->addAttribute('id', $user_id);//in userview: changed to user id
            $comment_tag->addChild('timestamp', $t_tstamp);
            $comment_tag->addChild('message', $sendMsg);  
            
            //save the file 
            //ERROR:: Failed to open stream: Permission denied (dont know how to fix that)
            //$XMLhelpdesk->saveXML("xml/helpdesk.xml");


        }
    }       
}
        





?>




<html>
    <head>
        <title>User View ticket</title>
        <meta charset="utf-8">
        <link href="css/viewStyle.css" rel="stylesheet">
    </head>
    <body>
        <h1>Your Tickets Details</h1>
        <?php for($i = 0; $i < sizeof($ticket_details); $i++){ ?>
        <main id="ticketdetail_div">
            <h3>Ticket No. <?= $ticket_details[$i]['ticketid']?> </h3>
            <div class="ticket_discription">
                <p><strong>Category:</strong> <span><?= $ticket_details[$i]->category ?></span></p>
                <p><strong>Subject: </strong><span><?= $ticket_details[$i]->subject ?></span></p>
                <p><strong>Description: </strong><span><?= $ticket_details[$i]->description?></span></p>
                <p><strong>Status: </strong><span><?= $ticket_details[$i]->status ?></span></p>
                <p><strong>Comments:</strong><br>
                <?php
                    foreach($ticket_comments as $t_comment){
                        //firgure out what user it is
                        if($t_comment['id'] == $user_id){
                            echo ('<br/><span><strong>User:</strong> ' . $ticket_details[$i]->ticketopenedby->firstname . ' ' . $ticket_details[$i]->ticketopenedby->lastname . '     ||     ' . $t_comment->timestamp . '</span><br/><span>' . $t_comment->message . '</span><br/>');
                        }
                        else {
                            echo ('<br/><span><strong>Employee:</strong> ' . $ticket_details[$i]->ticketassignedto->firstname . ' ' . $ticket_details[$i]->ticketassignedto->lastname . '      ||     ' . $t_comment->timestamp . '</span><br/><span>' . $t_comment->message . '</span><br/>');
                        } 
                    } 
                ?>
                </p>
                <?php } ?>
                <p>Message: <span></span></p>
                <form method="post">
                    <div class="messageBox">
                        <div>
                        <input type="text" class="form-control" name="messageBox" placeholder="message ...." />
                        </div>
                        <div>
                            <button type="submit" class="extlink" name="leaveComment">Send</button>
                        </div>
                    </div>
                </form>
                <button class="account_btn"><a href="user.php">Home</a></button>
            </div>
        </main>
    </body>
</html>