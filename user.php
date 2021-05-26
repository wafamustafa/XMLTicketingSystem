<?php 
//start session
session_start();
//getting the account id of the logged in user
//REMEMBER:: during log in we named attribute id as userid
$user_id = $_SESSION['userId'];

//create an empty array to hold ticket details for the user
$user_tickets = array();

//load XML file with ticket information 
$XMLhelpdesk = simplexml_load_file("xml/helpdesk.xml");

//loop through the file to find tickets where user_id = clientid
for($i = 0; $i < sizeof($XMLhelpdesk); $i++){
    //here is where we ensure logged user id matches the client id
    if ($XMLhelpdesk->ticket[$i]->ticketopenedby->clientid == $user_id){

        //start session variable for ticket to get the detail on the next page
        $data = (string)$XMLhelpdesk->ticket[$i]['ticketid'];
        $_SESSION['ticketId'] = $data;
        
        $data2 = (string)$XMLhelpdesk->ticket[$i]->ticketassignedto->employeeid;
        $_SESSION['employeeId'] = $data2;

        //where ever client id match add the info to empty array variable of that specfic ticket or tickets
        array_push($user_tickets, $XMLhelpdesk->ticket[$i]);
    }
}

//for personal welcome 
$XMLuser = simplexml_load_file("xml/user.xml");
for ($i = 0; $i < sizeof($XMLuser); $i++){
    if($XMLuser->account[$i]['id'] == $user_id){
        $personalheader =(string)$XMLuser->account[$i]->firstname;
    }
}

?>






<html>
    <head>
        <title>User View ticket</title>
        <meta charset="utf-8">
        <link href="css/userStyle.css" rel="stylesheet">
    </head>
    <body>
        <!--personalized greeting-->
        <h1>Welcome <?= $personalheader ?></h1>
        <main id="useraccount_div">
            <h2>Your Tickets</h2>
            <!--php opening tag-->
            <?php
            for($i = 0; $i < sizeof($user_tickets); $i++){ ?>
            <div class="useraccount_tickets">
                <table>
                    <thead>
                        <th>Ticket Id</th>
                        <th>Open Date</th>
                        <th>Employee Assigned</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    <tbody>
                    <!--brief ticket info-->
                        <td><?=$user_tickets[$i]['ticketid']?></td>
                        <td><?=$user_tickets[$i]->opendate?></td>
                        <td><?=$user_tickets[$i]->ticketassignedto->firstname.' '.$user_tickets[$i]->ticketassignedto->lastname?></td>
                        <td><?=$user_tickets[$i]->subject?></td>
                        <td><?=$user_tickets[$i]->status?></td>
                        <td>
                        <!--pulling ticket id to specify ticket id-->
                            <button type="submit" class="useraccount_tickets__detailbtn" name="tdetails" id="tdetails">
                                <a href="userView.php?id=<?= $user_tickets[$i]['ticketid'] ?>">View details</a>
                            </button>
                        </td>
                        <!--php closing tag-->
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div>
                <button class="useraccount_logoutbtn">
                    <a href="logout.php">Log out</a>
                </button>
            </div>
        </main>
    </body>
</html>