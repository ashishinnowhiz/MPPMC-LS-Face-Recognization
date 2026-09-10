var option = '';
var startX = 0;
var startY = 0;
var dragStart = false;
var canvasId = '';
var sheetMarking = new Array();
var eval_id = 1;
$('.pageCanvas').click(function(e) {
    dragStart = false;
});
$('.pageCanvas').mousedown(function(e) {
    startX = e.pageX;
    startY = e.pageY;
    if (e.button == 2) {
        if (selectedQue != null) {
            canvasId = this.id;
            $('#dragSquare').show();
            var offset = $("#" + this.id).offset();
            //alert(offset.top+"-"+offset.left);
            dragSet(e.pageX, e.pageY, 200, 50);
            $('#scoreBox').show();
            //$('#scoreBox').css('left', e.pageX);
            //$('#scoreBox').css('top', e.pageY-300);
            //$('#scoreBox').css('width', 400);
        } else {
            alert("Please select question to allot marks");
            $('#queNos').show();
        }
    } else {
        var display = $('#scoreBox').css("display");
        if (display == 'none') {
            dragStart = true;
        } else {
            $('#scoreBox').hide();
            dragReset();
        }
    }
});

function hideScore() {
    $('#scoreBox').hide();
}
$('.pageCanvas').mousemove(function(e) {
    if (selectedQue != null) {
        $('#trailText').html('Q ' + schemeObj[selectedQue].Question_No);
        $('#trailText').css('left', e.pageX + 10);
        $('#trailText').css('top', e.pageY + 10);
    }
    if (dragStart) {
        $('#dragSquare').show();
        var dragX = startX;
        var dragY = startY;
        if (e.pageX < startX) {
            dragX = e.pageX;
        }
        if (e.pageY < startY) {
            dragY = e.pageY;
        }
        var dragW = Math.abs(e.pageX - startX);
        var dragH = Math.abs(e.pageY - startY);
        if (selectedTool != 'rectangle') {
            if (dragW < 50) {
                dragW = 50;
            }
            if (dragH < 50) {
                dragH = 50;
            }

        }

        dragSet(dragX, dragY, dragW, dragH);
    }
});
$('.pageCanvas').mouseup(function(e) {
    if (dragStart) {
        var display = $('#dragSquare').css("display");
        if (display == 'none') {
            $('#dragSquare').show();
            dragSet(e.pageX, e.pageY, 50, 50);
        }
        var width = $('#dragSquare').width();
        var height = $('#dragSquare').height();
        var offset = $("#" + this.id).offset();
        var drag = $('#dragSquare').offset();

        var x = drag.left - offset.left;
        var y = drag.top - offset.top;

        var drawTool = selectedTool;
        if (drawTool == 0) {
            if (drag.left < startX) {
                drawTool = 'cross';
            } else {
                drawTool = 'check';
            }
        }
        var pageId = this.id;
        if (drawTool == 'comment') {
            var commentText = prompt("Please Input Comment", "");
        } else {
            var commentText = 'NA';
        }
        if (commentText.length > 0) {
            var obj = { drawTool, pageId, x, y, width, height, commentText, que: selectedQue, page: pageId.substr(4), sheet: sheet.sheet_file };

            var obj1 = {
                eval_id: eval_id,
                eval_page: pageId.substr(4),
                eval_action: drawTool,
                eval_details: JSON.stringify({ x: x, y: y, width: width, height: height, commentText: commentText }),
                eval_que_index: selectedQue,
                sheet: sheet.sheet_file,
                eval_role: 'Evaluator',
                eval_time: currentDateTime()
            };
            sheetMarking.push(obj1);

            //console.log(JSON.stringify(sheetMarking));
            /*var resp = getContent('sheet/addlog', obj);
                if(resp.success){
                    addUndo(pageId.substr(4),eval_id);
                    
                }else{
                    alert(resp.message);
                } */

            addUndo(pageId.substr(4), eval_id, selectedQue);
            eval_id++;
            drawCanvas(drawTool, pageId, x, y, width, height, commentText);
        }

        //$("#show").html(divPos.left+"-"+divPos.top);

        dragReset();
    }
});

function clearCanvas(pageId) {
    var ctx = document.getElementById(pageId).getContext("2d");
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height); // Clears the canvas
}

var pageMarked = new Array();

function drawCanvas(toDraw, pageId, x, y, width, height, commentText = '', userName = '') {
    var pageNum = pageId.substr(4);
    pageMarked[pageNum] = true;
    $('#docpageNum' + pageNum).addClass('marked');
    var zoomNow = document.getElementById("currentZoom").textContent;
    if (zoomNow != '100') {
        var zoomWidth = $("#" + pageId).width();
        var zoomHeight = $("#" + pageId).height();
        x = (x * canvasWidth) / zoomWidth;
        y = (y * canvasHeight) / zoomHeight;
        width = (width * canvasWidth) / zoomWidth;
        height = (height * canvasHeight) / zoomHeight;
    }
    var ctx = document.getElementById(pageId).getContext("2d");
    ctx.globalAlpha = 1;
    if (toDraw == 'question') {
        ctx.globalAlpha = 0.6;
    }
    if (userName == 'Head_Evaluator') {
        ctx.strokeStyle = headColor;
        ctx.fillStyle = headColor;
    } else if (userName == 'Evaluator') {
        ctx.strokeStyle = color;
        ctx.fillStyle = color;
    } else {
        ctx.strokeStyle = color;
        ctx.fillStyle = color;
    }


    ctx.lineWidth = 2;
    // ctx.rect(x,y,width,height);
    // ctx.stroke(); 

    if (width < height) {
        var inSide = width;
    } else {
        var inSide = height;
    }
    if (toDraw == 'check') {
        ctx.beginPath();
        ctx.moveTo(x, y + (0.75 * inSide));
        ctx.lineTo(x + (0.25 * inSide), y + inSide);
        ctx.lineTo(x + inSide, y);
        ctx.stroke();
    } else if (toDraw == 'cross') {
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineTo(x + inSide, y + inSide);
        ctx.moveTo(x, y + inSide);
        ctx.lineTo(x + inSide, y);
        ctx.stroke();
    } else if (toDraw == 'question') {
        ctx.font = height + "px LatoWeb";
        ctx.fillText("?", x, y + (0.8 * height));
    } else if (toDraw == 'comment') {
        ctx.font = "16px LatoWeb";
        //ctx.fillText(commentText,x,y+(20));
        printAtWordWrap(ctx, commentText, x, y + (20), 16, width);
    } else if (toDraw == 'time') {
        ctx.font = "13px LatoWeb";
        ctx.fillText(commentText, x, y + (20));
    } else if (toDraw == 'rectangle') {
        ctx.rect(x, y, width, height);
        ctx.stroke();
    } else if (toDraw == 'ellipse') {
        ctx.beginPath();
        ctx.ellipse(x + width / 2, y + height / 2, width / 2, height / 2, 0, 0, 2 * Math.PI);
        ctx.stroke();
    }
}

function printAtWordWrap(context, text, x, y, lineHeight, fitWidth) {
    fitWidth = fitWidth || 0;

    if (fitWidth <= 0) {
        context.fillText(text, x, y);
        return;
    }
    var words = text.split(' ');
    var currentLine = 0;
    var idx = 1;
    while (words.length > 0 && idx <= words.length) {
        var str = words.slice(0, idx).join(' ');
        var w = context.measureText(str).width;
        if (w > fitWidth) {
            if (idx == 1) {
                idx = 2;
            }
            context.fillText(words.slice(0, idx - 1).join(' '), x, y + (lineHeight * currentLine));
            currentLine++;
            words = words.splice(idx - 1);
            idx = 1;
        } else { idx++; }
    }
    if (idx > 0)
        context.fillText(words.join(' '), x, y + (lineHeight * currentLine));
}

function dragSet(dragX, dragY, dragW, dragH) {
    var drawTool = selectedTool;
    if (dragStart) {
        if (drawTool == 0) {
            $("#dragSquare div").hide();
            if (dragX < startX) {
                drawTool = 'cross';
            } else {
                drawTool = 'check';
            }
        }
        if (dragW < dragH) {
            var inSide = dragW;
        } else {
            var inSide = dragH;
        }
        if (drawTool == 'check') {
            $('#dragTick').css('width', inSide);
            $('#dragTick').css('height', inSide);
            $('#dragTick').show();
            $('#dragTick div').show();
        } else if (drawTool == 'cross') {
            $('#dragCross').show();
            $('#dragCross div').show();
            $('#dragCross').css('width', inSide);
            $('#dragCross').css('height', inSide);
            var diagonal = Math.sqrt(inSide * inSide + inSide * inSide);
            $('#dragCross div').css('width', diagonal);
        } else if (drawTool == 'question') {
            $('#dragQuestion').show();
            $('#dragQuestion').css('font-size', dragH);
        } else if (drawTool == 'rectangle') {
            $('#dragRectangle').show();
        } else if (drawTool == 'ellipse') {
            $('#dragEllipse').show();
        }
    }
    $('#dragSquare').css('left', dragX);
    $('#dragSquare').css('top', dragY);
    $('#dragSquare').css('width', dragW);
    $('#dragSquare').css('height', dragH);
}

function dragReset() {
    $("#dragSquare").hide();
    $("#dragSquare div").hide();
    dragStart = false;
    var width = $('#dragSquare').width(0);
    var height = $('#dragSquare').height(0);
}

/* var docScreen=document.getElementById("docDiv")
docScreen.onmouseover = function(e){
    //alert(e.pageX);
        if(e.pageX <= 90){
            var display=$('#pageNos').css("display");
            if(display=='none'){
                $('#pageNos').show();
            }
        }
}; */

$(document).ready(function() {
    $(".pageCanvas").mouseleave(function() {
        $('#trailText').html('');
    });
    document.oncontextmenu = function() { return false; }; //right click default disable
});

function currentDateTime() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }
    return today = yyyy + '-' + mm + '-' + dd + ' ' + h + ':' + m + ':' + s;
}



function saveImages() {
    var urlSheet = base_url + 'practice/saveMarkingLog';
    var minTime = sheet.min_eval_time * 60;
    if (minTime > timespent) {
        var msg = 'Marking time for this answersheet is set to ' + toHHMMSS(minTime) + '.';
        msg += '\nYour Marking time is only ' + toHHMMSS(timespent) + '.';
        msg += '\nPlease save after set minimum Marking time'
        alert(msg);
    } else {
        var unMarked = [];
        for (i = 1; i <= totalPages['doc']; i++) {
            if (pageMarked[i]) {

            } else {
                unMarked.push(i);
            }
        }
        if (unMarked.length > 0) {
            alert('You have not marked anything on pages : ' + unMarked.join());
            goToPage(unMarked[0]);
        } else {
            var tableData = $('#markScheme').html();
            $('#summaryTable').html(tableData);

            var table = document.getElementById("summaryTable");
            var row = table.insertRow();
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.innerHTML = "<b>Total</b>";
            cell2.innerHTML = totalScore + '/' + sheet.paper_total_marks;


            $('#summaryModal').modal('show');
            //finalSave();

        }
    }
}

function finalSave() {
    var isEval = true;
    var evalType = sheet.evalType;
    $("#finishBtn").hide();
    if ($('#confirmation').prop("checked") == true) {
        sheetMarking = new Array();
        alert('Sheet Successfully Saved');
        top.location.href = '';
        window.location.href = "";
    } else if ($('#confirmation').prop("checked") == false) {
        alert("Please acknowledge you have checked all pages by clicking checkbox below summary table");
        $("#finishBtn").show();
    }
}
var timespent = 0;
setInterval(function() {
    timespent = timespent + 1;
    //console.log("time:"+toHHMMSS(timespent));	
    $("#timer").html(toHHMMSS(timespent));
}, 1000);

var toHHMMSS = (secs) => {
    var sec_num = parseInt(secs, 10)
    var hours = Math.floor(sec_num / 3600) % 24
    var minutes = Math.floor(sec_num / 60) % 60
    var seconds = sec_num % 60

    return [hours, minutes, seconds]
        .map(v => v < 10 ? "0" + v : v)
        .filter((v, i) => v !== "00" || i > 0)
        .join(":")
}

function resetTimer() {
    var assignTime = Date.parse(sheet.sheet_assign_time);
    var time = Date.now();
    //console.log("time:"+time);	
    timespent = time / 1000;
}

var selectedTab = 'doc';

function selectTab(tabId) {
    selectedTab = tabId;
    $("#tabLinks a").removeClass("selected");
    $("#" + tabId + "Tab").addClass("selected");
    $("#tabs .flexHeight").hide();
    $("#" + tabId + "Div").show();

    $("#pageNos div").hide();
    $("#" + tabId + "PageNos").show();

    $("#currentPage").html(selectedPage[selectedTab]);
    $("#totalPageCount").html(totalPages[selectedTab]);
    if (tabId == 'ans') {
        goToPage(selectedPage[selectedTab]);
    }
}


var selectedPage = new Array();
selectedPage['que'] = 1;
selectedPage['ans'] = 1;
selectedPage['doc'] = 1;
$("#pageNum1").addClass("selected");

function currentPage() {
    var scrollDiv = document.getElementById(selectedTab + "Div");

    var pageHeight = 18 + parseInt(document.getElementById(selectedTab + "PageDiv1").offsetHeight);
    var scrollPosition = pageHeight + scrollDiv.scrollTop;
    var inPage = Math.round(scrollPosition / pageHeight);
    console.log('PaGe:' + inPage);
    if (inPage > 2) {
        inPage = Math.floor(scrollPosition / pageHeight) + 1;
    } else {
        inPage = Math.floor(scrollPosition / pageHeight);
    }
    if (inPage <= totalPages[selectedTab]) {
        selectedPage[selectedTab] = inPage;
        $("#currentPage").html(inPage);
        $("#" + selectedTab + "PageNos a").removeClass("selected");
        $("#" + selectedTab + "pageNum" + inPage).addClass("selected");
    }
}
var selectedQue = 0;

function selectQue(index) {
    //alert(schemeObj[index].MaxScore);
    selectedQue = index;

    if (scorePage[selectedQue] && selectedTab == 'doc') {
        goToPage(scorePage[selectedQue]);
    }

    setScore();
    nPage = schemeObj[index].Page;
    if (schemeObj[index].Page == '' || schemeObj[index].Page == '0') {
        nPage = 0;
    }
    if (selectedTab == 'ans') {
        goToPage(nPage)
    }
    selectedPage['ans'] = nPage;
    $("#queNos a").removeClass("selected");
    $("#queNum" + index).addClass("selected");
}

function setScore() {
    var linkContent = '';
    if ('bestObj' in sheet) {
        if (selectedQue in sheet.bestObj) {
            linkContent = "<a onclick=\"allotMark(" + sheet.bestObj[selectedQue] + ")\">" + sheet.bestObj[selectedQue] + "</a>";
        }
    }
    if (linkContent == '') {
        var maxScore = parseInt(schemeObj[selectedQue].MaxScore);
        var minScore = parseInt(schemeObj[selectedQue].MinScore);
        var i = minScore;
        if (allotedMarks[selectedQue] != 'NA') {
            if (allotedMarks[selectedQue] < 0) {
                i = minScore - allotedMarks[selectedQue];
            } else if (allotedMarks[selectedQue] > 0) {
                maxScore = maxScore - allotedMarks[selectedQue];
            }
        }
        while (i <= maxScore) {
            linkContent += "<a onclick=\"allotMark(" + i + ")\">" + i + "</a>";
            i = i + 0.5;
        }
    }

    $("#markLinks").html(linkContent);
    $("#markTitle").html('Q ' + schemeObj[selectedQue].Question_No);
}


function allotMark(score) {
    if (allotedMarks[selectedQue] == 'NA') {
        allotedMarks[selectedQue] = 0;
    }
    allotedMarks[selectedQue] += score;
    $('#queMark' + selectedQue).html(allotedMarks[selectedQue]);
    var width = $('#dragSquare').width();
    var height = $('#dragSquare').height();
    var offset = $("#" + canvasId).offset();
    var drag = $('#dragSquare').offset();
    var x = drag.left - offset.left;
    var y = drag.top - offset.top;

    var commentText = "Q." + schemeObj[selectedQue].Question_No + " : " + score;
    if (score == 0) {
        var remark = prompt("Please input remark for 0 marks", "");
        commentText += ' -' + remark;
        if (remark == '') {
            alert('Remark needed for o marks');
            commentText = '';
        }
    }
    if (commentText != '') {
        var obj = { 'drawTool': 'score', canvasId, x, y, width, height, commentText, score, que: selectedQue, page: canvasId.substr(4), sheet: sheet.sheet_file };
        //====================Added date on 29-08-2020===
        var obj1 = {
            eval_id: eval_id,
            eval_page: canvasId.substr(4),
            eval_action: 'score',
            eval_details: JSON.stringify({ x: x, y: y, width: width, height: height, score: score, commentText: commentText }),
            eval_que_index: selectedQue,
            sheet: sheet.sheet_file,
            eval_role: 'Evaluator',
            eval_time: currentDateTime()
        };
        sheetMarking.push(obj1);
        //console.log(JSON.stringify(sheetMarking));
        //====================Added date on 29-08-2020===		

        /* var resp = getContent('sheet/addlog', obj);
        if(resp.success){
            scorePage[selectedQue]=canvasId.substr(4);
            $('#queNum'+selectedQue).addClass('marked');
            addUndo(canvasId.substr(4),resp.id);
            icon='check';
            if(score<=0){
                icon='cross';
            }
            drawCanvas(icon,canvasId,x,y,30,30);
            drawCanvas('comment',canvasId,x+30,y,width,height,commentText);
            drawCanvas('time',canvasId,x+30,y+22,width,height,resp.time);
        }else{
            alert(JSON.stringify(resp));
        } */
        //====================Added date on 29-08-2020===
        scorePage[selectedQue] = canvasId.substr(4);
        $('#queNum' + selectedQue).addClass('marked');
        addUndo(canvasId.substr(4), eval_id, selectedQue);
        eval_id++;
        icon = 'check';
        if (score <= 0) {
            icon = 'cross';
        }
        drawCanvas(icon, canvasId, x, y, 30, 30);
        drawCanvas('comment', canvasId, x + 30, y, width, height, commentText);
        drawCanvas('time', canvasId, x + 30, y + 22, width, height, currentDateTime());
        //====================Added date on 29-08-2020===

        $('#scoreBox').hide();
        setScore();
        calculateTotal();
        dragReset();
    }
}
var totalScore = 0;
var leastIndexArray = [];

function calculateTotal() {
    leastIndexArray = [];
    $('.queNo').parent().parent().css('text-decoration', 'none');
    totalScore = 0;
    var marked = new Array();
    var lastGroupTotal = 0;
    var highetsIndex = schemeObj.length - 1;
    //console.log(JSON.stringify(schemeObj));
    for (i = highetsIndex; i >= 0; i--) {
        schemeObj[i].allotedMarks = allotedMarks[i];
        schemeObj[i].removed = false;
        if (allotedMarks[i] != 'NA') {
            if (schemeObj[i].Group != '') {
                var groupName = schemeObj[i].Group;
                var groupIndex = groupName.substring(5, groupName.length);
                if (marked[schemeObj[i].Group]) {
                    marked[schemeObj[i].Group] += 1;
                    allotedMarks[groupIndex - 1] += allotedMarks[i];
                } else {
                    marked[schemeObj[i].Group] = 1;
                    allotedMarks[groupIndex - 1] = allotedMarks[i];
                }
            } else {
                totalScore += allotedMarks[i];
            }
        }
    }
    for (var group in groups) {
        if (groups.hasOwnProperty(group)) {
            var indexes = groups[group];
            if (leastIndexArray.includes(group)) {

            } else {
                if (marked[group]) {
                    if (marked[group] > valid[group]) {
                        var rmCount = marked[group] - valid[group];
                        var removed = 0;
                        while (removed < rmCount) {
                            leastMark = null;
                            leastIndex = null;
                            for (var i = 0; i < indexes.length; i++) {
                                if (allotedMarks[indexes[i]] <= leastMark || leastIndex == null) {
                                    if (leastIndexArray.includes(indexes[i])) {

                                    } else {
                                        leastMark = allotedMarks[indexes[i]];
                                        leastIndex = indexes[i];
                                    }
                                }
                            }
                            if (schemeObj[leastIndex].Question_No == 'parent' || schemeObj[leastIndex].Question_No == 'condition') {
                                rmGroup(leastIndex);
                            }
                            totalScore = totalScore - leastMark;
                            $('#queNum' + leastIndex).parent().parent().css('text-decoration', 'line-through');
                            schemeObj[leastIndex].removed = true;
                            leastIndexArray[leastIndexArray.length] = leastIndex;
                            removed = removed + 1;
                        }

                        //for (var i = 0; i < indexes.length; i++) {
                        // alert(indexes[i]);
                        //}
                    }
                }
            }
        }
    }
    $('#givenMarks').html(totalScore);
}

function rmGroup(leastIndex) {
    var index = leastIndex + 1;
    var indexes = groups['group' + index];
    leastIndexArray[leastIndexArray.length] = 'group' + index;
    for (var i = 0; i < indexes.length; i++) {
        if (schemeObj[indexes[i]].Question_No == 'parent' || schemeObj[indexes[i]].Question_No == 'condition') {
            rmGroup(indexes[i]);
        } else {
            $('#queNum' + indexes[i]).parent().parent().css('text-decoration', 'line-through');
            schemeObj[indexes[i]].removed = true;
            leastIndexArray[leastIndexArray.length] = indexes[i];
        }
    }
}
var selectedTool = 0;

function selectTool(toolId) {
    selectedTool = toolId;
    $("#toolBar a").removeClass("selected");
    $("#" + toolId).addClass("selected");
}


function goToPage(pageNo) {

    var p = $("#" + selectedTab + "PageDiv" + pageNo);
    var position = p.position();
    $("#message").html("left: " + position.left + ", top: " + position.top);

    var $foo = $('#' + selectedTab + 'Div');
    //$foo.scrollTop($foo.scrollTop() + position.top);
    var topPos = $foo.scrollTop() + position.top;
    //var pageHeight = 20 + parseInt(document.getElementById(selectedTab+"PageDiv1").offsetHeight);
    //var topPos=pageHeight*(pageNo-1);
    $('#' + selectedTab + 'Div').animate({
        scrollTop: topPos
    }, 1000);
}

function nextPage() {
    var pageNow = document.getElementById("currentPage").textContent;
    var next = parseInt(pageNow) + 1;
    if (next <= totalPages[selectedTab]) {
        goToPage(next);
    }
}

function prevPage() {
    var pageNow = document.getElementById("currentPage").textContent;
    var prev = parseInt(pageNow) - 1;
    if (prev > 0) {
        goToPage(prev);
    }
}

document.getElementById("docDiv").onscroll = function() { currentPage() };
document.getElementById("queDiv").onscroll = function() { currentPage() };
document.getElementById("ansDiv").onscroll = function() { currentPage() };

function resizePage(screenSize) {
    if (screenSize == 'full') {
        var w = window.screen.width;
        var h = window.screen.height;
    } else {
        var w = window.innerWidth;
        var h = window.innerHeight;
    }
    $('.flexHeight').css('height', h - 110);
    $("#message").html(w + "*" + h);
}
resizePage('inner');

function showDiv(divId) {
    var display = $('#' + divId).css("display");
    $('#' + divId).show();
    if (display == 'block') {
        $('#' + divId).hide();
    }
}
/* Get the documentElement (<html>) to display the page in fullscreen */

var fullScreenOn = false;
/* View in fullscreen */
function openFullscreen() {
    $('#evalScreen').show();
    if (fullScreenOn) {
        closeFullscreen();
    } else {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) { /* Firefox */
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE/Edge */
            elem.msRequestFullscreen();
        }
        fullScreenOn = true;
        resizePage('full');
    }
}

/* Close fullscreen */
function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) { /* Firefox */
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE/Edge */
        document.msExitFullscreen();
    }
    fullScreenOn = false;
    setTimeout(function() { resizePage('inner'); }, 1000);
}

function closeEvalScreen() {
    var r = confirm("Want to close without submitting final marking?");
    if (r == true) {
        closeFullscreen();
        $('#evalScreen').hide();
        window.location.href = "";
    } else {

    }
}

$(window).resize(function() {
    resizePage('inner');
});


function zoom(set) {
    var currentWidth = $('.pageDiv').width();
    var zoomNow = document.getElementById("currentZoom").textContent;
    if (set == 'plus') {
        var percent = parseInt(zoomNow) + 10;
    } else if (set == 'minus') {
        var percent = parseInt(zoomNow) - 10;
    } else {
        var percent = 100;
    }
    var w = window.innerWidth;
    var newWidth = 960 * (percent / 100);
    if (newWidth < w && newWidth > (w / 2.5)) {
        $('.pageDiv').css('width', newWidth);
        var newHeight = $('.pageDiv').height();

        $('.pageCanvas').css('width', newWidth);
        $('.pageCanvas').css('height', newHeight);
        //$(".pageCanvas").attr("width",newWidth);
        //$(".pageCanvas").attr("height",newHeight);
        $('#currentZoom').html(percent);
        var pageNow = document.getElementById("currentPage").textContent;
        location.hash = "#" + selectedTab + "PageDiv" + pageNow;
        //goToPage(pageNow);
    }
}

document.onkeydown = function(e) {
    if (e.ctrlKey && e.keyCode) {
        e.preventDefault();
    }
    if (e.altKey && e.keyCode) {
        e.preventDefault();
    }

    if (e.ctrlKey && e.altKey && (e.keyCode == 46)) {
        e.preventDefault();
    }
    if ((e.keyCode >= 32) || (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90)) {
        //alert(e.keyCode);
    }
    /*     if(e.keyCode == 27){
            alert("ESC");
        } */
}

function reject() {
    $('#rejectModal').modal('show');
}

function getHelp() {
    $('#helpModal').modal('show');
}

function rejectSheet() {
    error = '';
    var otherText = '';
    var reason = $("#rejectReason").val();
    if (reason == '') {
        error = 'Please select reason to reject';
    } else if (reason == 'Other') {
        var otherText = $("#otherReason").val();
        if (otherText == '') {
            error = 'Please give other reason remarks';
        }
    }
    if (error != '') {
        alert(error);
    } else {

        top.location.href = '';

    }
}

function reasonChange(reason) {
    if (reason == 'Other') {
        $('#rejectOther').show();
    } else {
        $('#rejectOther').hide();
    }
}

function getContent(url, obj = '', async = false, callback = '') {
    var method = 'GET';
    var content = '';

    $.ajax({
        type: method,
        url: base_url + url,
        async: async,
        data: obj,
        dataType: 'json',
        success: function(data) {
            if (callback != '') {
                window[callback](data);
            } else {
                content = data;
            }
        },
        error: function(data) {
            content = data;
        }
    });
    if (callback == '') {
        return content;
    }
}

function getCheckedScript() {

    var table = document.getElementById("markScheme");

    var rowCount = table.rows.length;
    for (var i = rowCount - 1; i > 0; i--) {
        table.deleteRow(i);
    }



    if (sheet.notAttempted) {
        for (i = 0; i < schemeObj.length; i++) {
            if (jQuery.inArray(i, sheet.notAttempted) >= 0) {
                var row = table.insertRow();
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                cell1.innerHTML = "<a id='queNum" + i + "' onclick=\"selectQue('" + i + "')\" class='queNo'>" + schemeObj[i].Question_No + "</a>";
                cell2.innerHTML = "<span id='queMark" + i + "'>-</span>/" + schemeObj[i].MaxScore;
                schemeObj[i]['oldMarks'] = 'NA';
            }
            allotedMarks[i] = 'NA';
            if (schemeObj[i].Group != '') {
                var gname = schemeObj[i].Group;
                if (gname in groups) {

                } else {
                    groups[gname] = [];
                }
                groups[gname][groups[gname].length] = i;
                valid[gname] = schemeObj[i].Valid;
            }
        }
        //alert(JSON.stringify(schemeObj));
    } else {
        for (i = 0; i < schemeObj.length; i++) {
            // Create an empty <tr> element and add it to the 1st position of the table:
            var row = table.insertRow();

            // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);

            // Add some text to the new cells:
            if (schemeObj[i].Question_No == 'parent') {
                row.deleteCell(1);
                cell1.colSpan = 5;
                cell1.innerHTML = "Q " + schemeObj[i].MinScore + "</a>";
            } else if (schemeObj[i].Question_No == 'condition') {
                row.deleteCell(1);
                cell1.colSpan = 5;
                cell1.innerHTML = "Any " + schemeObj[i].MinScore + "/" + schemeObj[i].MaxScore;
            } else {
                cell1.innerHTML = "<a id='queNum" + i + "' onclick=\"selectQue('" + i + "')\" class='queNo'>" + schemeObj[i].Question_No + "</a>";
                cell2.innerHTML = "<span id='queMark" + i + "'>-</span>/" + schemeObj[i].MaxScore;
            }
            allotedMarks[i] = 'NA';
            if (schemeObj[i].Group != '') {
                var gname = schemeObj[i].Group;
                if (gname in groups) {

                } else {
                    groups[gname] = [];
                }
                groups[gname][groups[gname].length] = i;
                valid[gname] = schemeObj[i].Valid;
            }
            //$('#markScheme').append("<tr><td></td><td>"++"</td></tr>");
        }
    }

}
var allotedMarks = new Array();
var scorePage = new Array();
var schemeObj = new Object();
var totalPages = new Array();
var groups = {};
var valid = new Array();
var sheet = new Object();

function getSheet() {
    $("#evalMessage").html('Loading Script, Please wait ...');
    sheet = {"sheet_file":"samplesheet.pdf","allocation_id":"sample","medium_code":"E","paper_code":"samplepaper","subject_code":"samplepaper","sheet_status":"Rechecked","evaluation_date":"2023-06-01","sheet_assign_time":"2023-06-01 14:16:47","success":true,"url":"answersheets\/sample\/samplesheet.pdf","paper_model_question":"answersheets\/uploads\/model\/samplepaper.pdf","paper_total_marks":"70","paper_all_marks":"70","paper_passing_marks":"28","min_eval_time":"0","paper_model_answer":"answersheets\/uploads\/model\/sample.pdf"};

    if (sheet.success) {
        openFullscreen();

        // $('#verifyCheckedSheet').modal('show');
        $('#sheetFile').html(sheet.sheet_file);
        $("#totalMarks").html(sheet.paper_total_marks);
        var details = getContent('practice/details?url=' + sheet.url);
        var res = getContent('practice/resolution?url=' + sheet.url);
        resetCanvas(res.size.width, res.size.height);

        var scheme = {
            "success": true,
            "json": [
                { "Question_No": "condition", "MinScore": "4", "MaxScore": "6", "Valid": "", "Group": "" },
                { "Question_No": "1(a)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group1" },
                { "Question_No": "1(b)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group1" },
                { "Question_No": "1(c)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group1" },
                { "Question_No": "1(d)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group1" },
                { "Question_No": "1(e)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group1" },
                { "Question_No": "1(f)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group1" },
                { "Question_No": "condition", "MinScore": "4", "MaxScore": "6", "Valid": "", "Group": "" },
                { "Question_No": "2(a)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group8" },
                { "Question_No": "2(b)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group8" },
                { "Question_No": "2(c)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group8" },
                { "Question_No": "2(d)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group8" },
                { "Question_No": "2(e)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group8" },
                { "Question_No": "2(f)", "MinScore": "0", "MaxScore": "3.5", "Hint": "", "Page": "0", "Valid": "4", "Group": "group8" },
                { "Question_No": "condition", "MinScore": "2", "MaxScore": "3", "Valid": "", "Group": "" },
                { "Question_No": "3(a)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group15" },
                { "Question_No": "3(b)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group15" },
                { "Question_No": "3(c)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group15" },
                { "Question_No": "condition", "MinScore": "2", "MaxScore": "3", "Valid": "", "Group": "" },
                { "Question_No": "4(a)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group19" },
                { "Question_No": "4(b)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group19" },
                { "Question_No": "4(c)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group19" },
                { "Question_No": "condition", "MinScore": "2", "MaxScore": "3", "Valid": "", "Group": "" },
                { "Question_No": "5(a)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group23" },
                { "Question_No": "5(b)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group23" },
                { "Question_No": "5(c)", "MinScore": "0", "MaxScore": "7", "Hint": "", "Page": "0", "Valid": "2", "Group": "group23" }
            ]
        };


        schemeObj = scheme.json;
        groups = {};
        //$('#docPageDiv1 img').attr('src', 'sheet/page?num=1&url='+sheet.url);

        var table = document.getElementById("markScheme");

        var rowCount = table.rows.length;
        for (var i = rowCount - 1; i > 0; i--) {
            table.deleteRow(i);
        }



        if (sheet.notAttempted) {
            for (i = 0; i < schemeObj.length; i++) {
                if (jQuery.inArray(i, sheet.notAttempted) >= 0) {
                    var row = table.insertRow();
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    cell1.innerHTML = "<a id='queNum" + i + "' onclick=\"selectQue('" + i + "')\" class='queNo'>" + schemeObj[i].Question_No + "</a>";
                    cell2.innerHTML = "<span id='queMark" + i + "'>-</span>/" + schemeObj[i].MaxScore;
                    schemeObj[i]['oldMarks'] = 'NA';
                }
                allotedMarks[i] = 'NA';
                if (schemeObj[i].Group != '') {
                    var gname = schemeObj[i].Group;
                    if (gname in groups) {

                    } else {
                        groups[gname] = [];
                    }
                    groups[gname][groups[gname].length] = i;
                    valid[gname] = schemeObj[i].Valid;
                }
            }
            //alert(JSON.stringify(schemeObj));
        } else {
            for (i = 0; i < schemeObj.length; i++) {
                // Create an empty <tr> element and add it to the 1st position of the table:
                var row = table.insertRow();

                // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);

                // Add some text to the new cells:
                if (schemeObj[i].Question_No == 'parent') {
                    row.deleteCell(1);
                    cell1.colSpan = 5;
                    cell1.innerHTML = "Q " + schemeObj[i].MinScore + "</a>";
                } else if (schemeObj[i].Question_No == 'condition') {
                    row.deleteCell(1);
                    cell1.colSpan = 5;
                    cell1.innerHTML = "Any " + schemeObj[i].MinScore + "/" + schemeObj[i].MaxScore;
                } else {
                    cell1.innerHTML = "<a id='queNum" + i + "' onclick=\"selectQue('" + i + "')\" class='queNo'>" + schemeObj[i].Question_No + "</a>";
                    cell2.innerHTML = "<span id='queMark" + i + "'>-</span>/" + schemeObj[i].MaxScore;
                }
                allotedMarks[i] = 'NA';
                if (schemeObj[i].Group != '') {
                    var gname = schemeObj[i].Group;
                    if (gname in groups) {

                    } else {
                        groups[gname] = [];
                    }
                    groups[gname][groups[gname].length] = i;
                    valid[gname] = schemeObj[i].Valid;
                }
                //$('#markScheme').append("<tr><td></td><td>"++"</td></tr>");
            }
        }



        totalPages['doc'] = details.pageCount;

        $("#totalPageCount").html(totalPages['doc']);

        var pageContent = '';
        for (i = 1; i <= totalPages['doc']; i++) {
            $('#docPageDiv' + i).show();
            //$('#docPageDiv'+i+' img').attr('src', 'sheet/page/'+(i-1)+'?url='+sheet.url);
            pageContent += "<a id='docpageNum" + i + "' onclick=\"goToPage('" + i + "')\" class='pageNo'>" + i + "</a>";
            //var data=getContent('sheet/pageLog/'+i);
            //drawActions(data.actions);
        }
        $('#docPageNos').html(pageContent);

        $('#docPageDiv1 img').attr('src', base_url + 'practice/page/0?url=' + sheet.url);
        //$('#docPageDiv1 img').attr('src', base_url+'sheet/page/1?url='+sheet.url);
        //first page actions draw
        //var data=getContent('sheet/pageLog/1');
        //drawActions(data.actions);

        /*
		
        if(sheet.skipped){
            alert(sheet.skipped[i]);
            var skipcount=sheet.skipped.length;
            for (i = 0; i < skipcount; i++) {
                $('#queNum'+sheet.skipped[i]).addClass('skipped');
            }
            if(skipcount>0){
                alert('Questions marked red are important. Please ensure you mark them');
            }
        }
        */
        for (i = 2; i <= totalPages['doc']; i++) {
            //var data=getContent('sheet/pageLog/'+i);
            //drawActions(data.actions);
            //  $('#docPageDiv'+i+' img').attr('src', base_url+'sheet/page/'+(i)+'?url='+sheet.url);
            $('#docPageDiv' + i + ' img').attr('src', base_url + 'practice/page/' + (i - 1) + '?url=' + sheet.url);
            // getContent('sheet/pageLog/'+i,'',true,'loadActions');
        }


        $("#evalMessage").html('Loading model answer, Please wait ...');
        getContent('practice/details?url=' + sheet.paper_model_answer, '', true, 'loadModel');

        //$("#evalMessage").html('Loading paper, Please wait ...');
        //getContent('sheet/details?url='+sheet.paper_model_question,'',true,'loadPaper');

        resetTimer();
        $("#evalMessage").html('');
    } else {
        $("#evalMessage").html(sheet.message);
        $("#evalButton").attr("disabled", "disabled");
    }

}

function loadModel(ansDetails) {
    totalPages['ans'] = ansDetails.pageCount;
    var pageContent = '';
    for (i = 1; i <= totalPages['ans']; i++) {
        $('#ansPageDiv' + i).show();
        $('#ansPageDiv' + i + ' img').attr('src', base_url + 'practice/page/' + (i - 1) + '?url=' + sheet.paper_model_answer + '&&ans=ans');
        pageContent += "<a id='anspageNum" + i + "' onclick=\"goToPage('" + i + "')\" class='pageNo'>" + i + "</a>";
    }
    $('#ansPageNos').html(pageContent);
}

function loadPaper(queDetails) {
    totalPages['que'] = queDetails.pageCount;
    var pageContent = '';
    for (i = 1; i <= totalPages['que']; i++) {
        $('#quePageDiv' + i).show();
        $('#quePageDiv' + i + ' img').attr('src', base_url + 'practice/page/' + (i - 1) + '?url=' + sheet.paper_model_question);
        pageContent += "<a id='quepageNum" + i + "' onclick=\"goToPage('" + i + "')\" class='pageNo'>" + i + "</a>";
    }
    $('#quePageNos').html(pageContent);
}

function loadActions(data) {
    //var data=getContent('sheet/pageLog/'+i);
    drawActions(data.actions);
}

var undoStack = new Array();
var redoStack = new Array();
var redoStackData = new Array();

function addUndo(lastPage, lastAction, selectedQue) {
    redoStack = new Array();
    var undoMax = 6;
    if (undoStack.length > 5) {
        //undoStack.shift(); //removes first element
    }
    undoStack.push(lastPage + "/" + lastAction + "/" + selectedQue);
    console.log(undoStack);
}

function drawActions(actions, scoreCalc = true) {
    var i;
    for (i = 0; i < actions.length; i++) {
        drawAction(actions[i], scoreCalc);
    }
}

function drawAction(action, scoreCalc = true) {
    //console.log("drawAction"+JSON.stringify(action));
    var detail = JSON.parse(action.eval_details);
    x = parseFloat(detail.x);
    y = parseFloat(detail.y);
    width = parseFloat(detail.width);
    height = parseFloat(detail.height);
    drawTool = action.eval_action;
    pageId = 'page' + action.eval_page;
    commentText = detail.commentText;
    //drawCanvas(drawTool,pageId,x,y,width,height,commentText);
    if (drawTool == 'score') {
        scorePage[action.eval_que_index] = action.eval_page;

        if (action.user_role != 'Head_Evaluator') {
            if (scoreCalc) {
                var score = parseFloat(detail.score);
                if (allotedMarks[action.eval_que_index] == 'NA') {
                    allotedMarks[action.eval_que_index] = 0;
                }
                allotedMarks[action.eval_que_index] += score;
                calculateTotal();
            }
        }

        $('#queNum' + action.eval_que_index).addClass('marked');
        $('#queMark' + action.eval_que_index).html(allotedMarks[action.eval_que_index]);
        icon = 'check';
        if (score <= 0) {
            icon = 'cross';
        }
        drawCanvas(icon, pageId, x, y, 30, 30, '', action.user_role);
        drawCanvas('comment', pageId, x + 30, y, width, height, commentText, action.user_role);
        drawCanvas('time', pageId, x + 30, y + 22, width, height, action.eval_time, action.user_role);
    } else {
        drawCanvas(drawTool, pageId, x, y, width, height, commentText, action.user_role);
    }
}

function undo() {

    if (sheetMarking.length > 0) {

        var undoActionData = sheetMarking.filter(function(v, i) {
            return (v["eval_que_index"] == selectedQue);
        });
        console.log("undoActionData:" + JSON.stringify(sheetMarking));


        if (undoActionData.length > 0) {
            var index = undoActionData.length - 1;

            var ind = sheetMarking.findIndex(function(element) {
                return (element['eval_id'] == undoActionData[index].eval_id && element['eval_page'] == undoActionData[index].eval_page && element["eval_que_index"] == undoActionData[index].eval_que_index);
            });

            if (ind !== -1) {
                sheetMarking.splice(ind, 1)
            }

            var data = sheetMarking.filter(function(v, i) {
                return v["eval_page"] == undoActionData[index].eval_page;
            });
            var action = undoActionData[index];
            redoStack.push(undoActionData[index]);
            if (action.eval_action == 'score') {
                var detail = JSON.parse(action.eval_details);
                var score = parseFloat(detail.score);
                allotedMarks[action.eval_que_index] = allotedMarks[action.eval_que_index] - score;
                $('#queMark' + action.eval_que_index).html(allotedMarks[action.eval_que_index]);
                setScore();
                calculateTotal();
            }

            clearCanvas('page' + undoActionData[index].eval_page);
            console.log(JSON.stringify(data));
            if (data.length == 0) {
                $('#docpageNum' + undoActionData[index].eval_page).removeClass('marked');
            }
            drawActions(data, false);

        } else {
            alert('Nothing to Undone or limit reached');
        }
    } else {
        alert('Nothing to Undone or limit reached');
    }
}

function redo() {
    if (redoStack.length > 0) {
        var undoActionData = redoStack.filter(function(v, i) {
            return (v["eval_que_index"] == selectedQue);
        });
        if (undoActionData.length > 0) {
            var index = undoActionData.length - 1;
            sheetMarking.push(undoActionData[index]);
            var ind = redoStack.findIndex(function(element) {
                return (element['eval_id'] == undoActionData[index].eval_id && element['eval_page'] == undoActionData[index].eval_page);
            });
            if (ind !== -1) {
                redoStack.splice(ind, 1)
            }
            drawAction(undoActionData[index]);
            setScore();
        } else {
            alert('Nothing to Redone or limit reached');
        }
    } else {
        alert('Nothing to Redone or limit reached');
    }
}

function notify(status, message) {
    alert(message);
}
var canvasWidth = 958;
var canvasHeight = 0;

function resetCanvas(width, height) {
    canvasHeight = (height / width) * canvasWidth;
    $(".pageCanvas").attr("width", canvasWidth);
    $(".pageCanvas").attr("height", canvasHeight);
}

var refresh = 0;

function rotate() {
    refresh = refresh + 1;
    var i = selectedPage['doc'];
    var data = getContent('practice/rotate/' + (i - 1) + '?url=' + sheet.url);

    if (data.status) {
        var str = $('#docPageDiv' + i + ' img').css('transform');
        if (str == 'none') {
            $('#docPageDiv' + i + ' img').css('transform', 'rotate(180deg)');
        } else {
            $('#docPageDiv' + i + ' img').attr('style', 'width:100%');
        }
    }

    //$('#docPageDiv'+i+' img').attr('src', 'sheet/page/'+(i-1)+'?refresh='+refresh+'&url='+sheet.url);
}