<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <div class='row'>


        <div class='col-lg-6'>


       Paper Code  : <?php echo $papersmaking['paper_code'];?>


        </div>


        <div class='pull-right col-lg-6 text-right'>


        <?php
        $groupflag=false;
        if ($papersmaking['paper_total_marks']<$papersmaking['paper_all_marks']) {
            echo "Total Marks : <span id=''>".$papersmaking['paper_total_marks']."</span>";
            $groupflag=true;
        } else {
            echo "<input type='hidden' id='validateGroup' value='0'>";
            echo "Total Marks : <span id='sptotalmarkid'>".$papersmaking['paper_total_marks']."</span>";
        }
            
        ?>
         &nbsp;  
        
        <?php
        if ($groupflag) {
            echo "<input type='hidden' id='validateGroup' value='1'>";
            echo "All Marks : <span id='sptotalmarkid'>".$papersmaking['paper_all_marks']."</span>";
        }
        ?>
        </div>
    </div>
        <?php
           $temp=json_decode($papersmaking['marking_scheme_json'], true);

        if (sizeof($temp)>0) {
            $rownum=sizeof($temp);
        } else {
            $rownum=1;
        }
        ?>
       <input type="hidden" value="<?php echo $rownum; ?>" id="hiddsrno">
       <input type="hidden" value="<?php echo $papersmaking['paper_id']; ?>" id="hiddpaperid" name="hiddpaperid">

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id='mytable'>
                                    <thead>
                                        <tr>
                                            <th>Q.No</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                            <th>Hint</th>
                                            <th>Page</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $temp1=0;
                                        if (count($temp)==0) {
                                            ?>
                                        Marking Scheme not updated
                                        <?php } else {
                                            for ($i=0; $i<count($temp); $i++) {
                                                $temp1=$temp1+$temp[$i]["MaxScore"];
                                                if($temp[$i]["Question_No"]!='condition'){
                                                    if ($temp[$i]["Group"]!='') {
                                                        $groups[$temp[$i]["Group"]]=$temp[$i]["Valid"];
                                                        $group_questions[$temp[$i]["Group"]][$i+1]=$temp[$i]["Question_No"];
                                                    }
                                                }
                                                ?>
                                        <tr>

<?php if($temp[$i]["Question_No"]=='parent'){ ?>

<td colspan='5'>
Que <?php echo $temp[$i]["MinScore"]; ?> has <?php echo $temp[$i]["MaxScore"]; ?> Sub Questions</td>
              
<?php }else if($temp[$i]["Question_No"]=='condition'){ ?>
<td colspan='5'>Any <?php echo $temp[$i]["MinScore"]; ?> from <?php echo $temp[$i]["MaxScore"]; ?></td>
                
<?php }else{ 
if($temp[$i]["Group"]==''){
    $totalMarks=$totalMarks+$temp[$i]["MaxScore"];
}
?>
                                           <td><?php echo $temp[$i]["Question_No"]; ?></td>
                                           <td><?php echo $temp[$i]["MinScore"]; ?></td>
                                           <td><?php echo $temp[$i]["MaxScore"]; ?></td>
                                            <td><?php echo $temp[$i]["Hint"]; ?></td>
                                            <td><?php echo $temp[$i]["Page"]; ?></td>
<?php } 
?>
                                        </tr>
                                            <?php }
                                        } ?>


                                    </tbody>


                                </table>


                            </div>
                            <div class="table-responsive" id='groups' style='display:none;' <?php // if (!isset($groups)) { echo "style='display:none;'"; } ?>>
                                <table class="table table-striped table-hover" id='grouptable'>
                                    <thead>
                                        <tr>
                                            <th>Group Id Generated</th>
                                            <th>Max Valid Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //$temp1=0;
                                        if (isset($groups)) {
                                            foreach ($groups as $id => $val) { ?>
                                            <tr>
                                            <td><?php echo $id; ?></td>
                                            <td><?php echo $val; ?> valid from -
                                                <?php foreach ($group_questions[$id] as $qid => $value) {
                                                    echo "<a class='btn btn-default btn-sm'>".$value."</a> ";
                                                } ?>
                                            </td></tr>
                                            <?php }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
