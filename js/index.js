var pageid = getParameterByName('pageid'); // "lorem"
var nextpage = +pageid + 1;
var prevpage = +pageid - 1;
var pagelink = "";
var postDateId;

//page handler

function changepage(state) {
    if (state == "next") {
        window.location.href = "/?pageid=" + nextpage;

    } else if (state == "prev") {
        window.location.href = "/?pageid=" + prevpage;
    } else {
        console.log("error");
    }
}

$(document).ready(function() {
    checkbuttons();
    document.title = "Hyperchan: page " + pageid;
});

$.getJSON("/api/retrieve.php?page=" + pageid, function(data) {
    var html = '';
    $.each(data, function(key, value) {
        html += '<div id="post" class="hyper-container hyper-card hyper-white hyper-round hyper-margin">';
        //html += '<hr>'
        postDateId = new Date(value.dateid * 1000);
        html += '<h6>' + postDateId.toLocaleDateString('en-AU') + '&emsp; id: ' + value.postid + '&emsp; [<a href=\"/thread.html?thread=' + value.postid + '\">Reply</a>]' + '</h6>';
        html += '<div id="posttextandimage">';
        html += '<div id="postimage">';
        if (value.imageenabled == "1") {
            html += '<a href=\"' + './img/posts/' + value.postid + '.' + value.imageext + '\">'
            html += '<img src=\"' + './img/posts/' + value.postid + '.' + value.imageext + '" alt="image unavailable"' + ">";
            html += '</a>'
        } else {}
        html += '</div>';
        html += '<div>';
        var posttextdataformatted = value.post.replace(new RegExp('\r?\n', 'g'), '<br />');
        html += '<p>' + posttextdataformatted + '</p>';
        html += '</div>'
        html += '<img src="./img/assets/post_expand_plus.png" id=\"expandicon' + value.postid + '\" onclick=\"comment(' + value.postid + ')\" height=\"18\" width=\"18\">'
        html += '<div id=\"comment-section-' + value.postid + '\">'
        html += '</div>'
        html += '</div>';
        html += '</div>';
    });
    $('#post-section').html(html);
});

//comment retriever / handler
function comment(postnumber) {
    console.log(postnumber);

    document.getElementById('expandicon' + postnumber).src = './img/assets/post_expand_rotate.gif'

    $.getJSON("/api/retrieve.php?thread=" + postnumber, function(data) {
        var comment = '';
        $.each(data, function(key, value) {
            comment += '<div class="reply">';

            if (value.imageenabled == "1") {
                comment += '<div id="replyimage">';
                comment += '<a href=\"' + './img/comments/' + value.commentid + '.' + value.imageext + '\">'
                comment += '<img src=\"' + './img/comments/' + value.commentid + '.' + value.imageext + '" alt="image unavailable"' + ">";
                comment += '</a>';
                comment += '</div>';
            } else {}
            comment += '<p">' + value.comment + '</p>' + '<br>';
            comment += '</div>';

        });
        $('#comment-section-' + postnumber).html(comment);
    });

    document.getElementById('expandicon' + postnumber).src = './img/assets/post_expand_minus.png';
    document.getElementById('expandicon' + postnumber).setAttribute('onclick', 'commentoff(' + postnumber + ')');
}

function commentoff(postnumber) {
    document.getElementById('comment-section-' + postnumber).innerHTML = '';
    document.getElementById('expandicon' + postnumber).src = './img/assets/post_expand_plus.png';
    document.getElementById('expandicon' + postnumber).setAttribute('onclick', 'comment(' + postnumber + ')');

}

function postformdisplay() {
    var postform = '<hr><form id="postform" action="/api/post.php" method="POST" enctype="multipart/form-data">Post: <textarea type="text" class="form-control" name="post" placeholder="post" required></textarea><p></p>Image: <input name="file" type="file" id="image" /><p></p><input name="submit" type="submit" value="Upload" /></form><hr>';
    $('#postform').html(postform);
}

function checkbuttons() {
    if (+pageid >= 1 && pageid <= 9) {
        var buttondisplay = '<button onclick="changepage(\'prev\')"> Previous </button><button onclick="changepage(\'next\')"> Next </button>';
        $('#buttonarea').html(buttondisplay);
    } else if (+pageid == 0) {
        var buttondisplay = '<button onclick="changepage(\'next\')"> Next </button>';
        $('#buttonarea').html(buttondisplay);
    } else if (+pageid == 10) {
        var buttondisplay = '<button onclick="changepage(\'prev\')"> Previous </button>';
        $('#buttonarea').html(buttondisplay);
    } else {
        outofrange();
    }
}

function outofrange() {
    document.getElementById("postform").innerHTML = "";
    document.getElementById("buttonarea").innerHTML = "";
    var err404 = '<div id=\"404\" class=\"hyper-container hyper-card hyper-white hyper-round hyper-margin\"><center><br><h1>Error 404</h1><p>Page not found</p><p><a href=\"/\">Return to page 1</a></p><br><br><br></center></div>';
    document.getElementById("post-section").innerHTML = err404;
}

// Url handler
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return '0';
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
