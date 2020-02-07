
<h2 class="ui center aligned icon header">
    <i class="circular users icon"></i>
    Dashboard
</h2>
<?php
$stModel = new \center\models\StudentInfo();
$students = $stModel->getAllStudentsInfo();
$noOfStudents = (!empty($students['total'])) ? $students['total'] : 0;

//completed applications
$where = [];
$where[] = ['isApproved',1,'='];
$approved = $stModel->getSingleStudentMeta($where);
$noOfApprovedApplications = (!empty($approved['total'])) ? $approved['total'] : 0;

// waiting for pastor's response
$where = [];
$where[] = ['isApproved',0,'='];
$approved = $stModel->getSingleStudentMeta($where);
$WaitingApplications = (!empty($approved['total'])) ? $approved['total'] : 0;
//print_r($approved);
?>
<div class="ui three raised cards">
    <div class=" card">
        <div class="content">
            <div class="header"><?= $noOfStudents?> Total Entries</div>
            <a class="ui red right ribbon label">Registered Students</a>
            <div class="meta">Total number of applications.</div>
            <div class="description">
                This includes every applications (Incl. Archived too)

            </div>
        </div>
        <!--<div class="extra content">
            <a href="<?/*= menu_page_url("sp-past",false);*/?>" class="ui button">Check List</a>
        </div>-->
    </div>
    <div class="card">

        <div class="content">

            <div class="header"><?= $noOfApprovedApplications?> Approved</div>
            <a class="ui olive right ribbon label">Approved!</a>
<!--            <div class="meta">Uploaded File(s) in this plugin</div>-->
            <div class="description">

                Approved applications.
            </div>
        </div>
        <!--<div class="extra content">
            <a href="<?/*= menu_page_url('sp-past',false);*/?>" class="ui button">Check List </a>
        </div>-->
    </div>

    <div class="card">
        <div class="content">

            <div class="header"><?= $WaitingApplications?> Waiting for response</div>
            <a class="ui teal right ribbon label">Waiting for approval</a>
            <div class="meta">Approval from pastor</div>
            <div class="description">

                Applications are waiting to get approval from pastor
            </div>
        </div>
        <!--<div class="extra content">
            <a href="<?/*= menu_page_url('sp-past',false);*/?>" class="ui button">Check Collection Codes</a>
        </div>-->
    </div>
</div>


