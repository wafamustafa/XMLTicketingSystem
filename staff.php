<?php 
//start session
session_start();
//getting the account id of the logged in employee
//REMEMBER:: during log in we named attribute id as employeeid
$employee_id = $_SESSION['employeeId'];

//create an empty array to hold ticket details for the user
$employee_tickets = array();

//load XML file with ticket information 
$XMLhelpdesk = simplexml_load_file("xml/helpdesk.xml");

//loop through the file to find tickets where employee_id = employeeid
for($i = 0; $i < sizeof($XMLhelpdesk); $i++){
    //here is where we ensure logged user id matches the employee id
    if ($XMLhelpdesk->ticket[$i]->ticketassignedto->employeeid == $employee_id){

        //start session variable for ticket to get the detail on the next page
        $data = (string)$XMLhelpdesk->ticket[$i]['ticketid'];
        $_SESSION['ticketId'] = $data;
        $data4 = (string)$XMLhelpdesk->ticket[$i]->ticketopenedby->clientid;
        //changes to client id
        $_SESSION['userId'] = $data4;

        //where ever client id match add the info to empty array variable of that specfic ticket or tickets
        array_push($employee_tickets, $XMLhelpdesk->ticket[$i]);
    }
}

//for personal welcome 
$XMLuser = simplexml_load_file("xml/user.xml");
for ($i = 0; $i < sizeof($XMLuser); $i++){
    if($XMLuser->account[$i]['id'] == $employee_id){
        $personalheader =(string)$XMLuser->account[$i]->firstname;
    }
}


?>


<html>
    <head>
        <title>Staff View ticket</title>
        <meta charset="utf-8">
        <link href="css/staffStyle.css" rel="stylesheet">
    </head>
    <body>
        <!--personalized greeting-->
        <h1>Welcome <?= $personalheader ?></h1>
        <main id="staffaccount_div">
            <h2>All Tickets</h2>
            <!--php opening tag-->
            <?php
            for($i = 0; $i < sizeof($employee_tickets); $i++){ ?>
            <div class="staffaccount_tickets">
                <table>
                    <thead>
                        <th>Ticket Id</th>
                        <th>Open Date</th>
                        <th>Client Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <!--brief ticket info-->
                        <td><?=$employee_tickets[$i]['ticketid']?></td>
                        <td><?=$employee_tickets[$i]->opendate?></td>
                        <td><?=$employee_tickets[$i]->ticketopenedby->firstname.' '.$employee_tickets[$i]->ticketopenedby->lastname?></td>
                        <td><?=$employee_tickets[$i]->subject?></td>
                        <td><?=$employee_tickets[$i]->status?></td>
                        <td>
                        <!--pulling ticket id to specify ticket id-->
                            <button type="submit" class="staffaccount_tickets__detailbtn" name="tdetails" id="tdetails">
                                <a href="staffView.php?id=<?= $employee_tickets[$i]['ticketid'] ?>">View details</a>
                            </button>
                        </td>
                        <!--php closing tag-->
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div>
                <button class="staffaccount_logoutbtn"><a href="logout.php">Log out</a></button>
            </div>
        </main>
    </body>
</html>