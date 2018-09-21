var threadid = getParameterByName('thread'); // "lorem"
//var nextpage = +pageid +1;
//var prevpage = +pageid -1;
var pagelink = "";
var postDateId;


$( document ).ready(function() {
    console.log( "loading!" );
    $.getJSON("/api/retrieve.php?post=" + threadid, function(data) {
        var html = '';
        $.each(data, function(key, value){
            html += '<div id="post" class="hyper-container hyper-card hyper-white hyper-round hyper-margin">';
            //html += '<hr>'
            postDateId = new Date(value.dateid * 1000);
            html += '<h6>' + postDateId.toLocaleDateString('en-AU') + '&emsp; id: ' + value.postid + '</h6>';

            html += '<div id="posttextandimage">';

            html += '<div id="postimage">';
            if (value.imageenabled == "1"){
              html += '<a href=\"' + './img/posts/' + value.postid + '.' + value.imageext + '\">'
              html += '<img src=\"' + './img/posts/' + value.postid + '.' + value.imageext + '" alt="image unavailable"' + ">";
              html += '</a>'
            }
            else{}
            html += '</div>';
            html += '<div>'
            html += '<p>' + value.post + '</p>';
            html += '</div>'
            html += '<div id=\"comment-section\">'
            html += '</div>';
            html += '</div>';
        });
    $('#post-section').html(html);
    });

//load replies
    $.getJSON("/api/retrieve.php?thread=" + threadid, function(data) {
        var comment = '';
        $.each(data, function(key, value){
            comment += '<div class="reply">';

            if (value.imageenabled == "1"){
              comment += '<div id="replyimage">';

              comment += '<a href=\"' + './img/comments/' + value.commentid + '.' + value.imageext + '\">'
              comment += '<img src=\"' + './img/comments/' + value.commentid + '.' + value.imageext + '" alt="image unavailable"' + ">";
              comment += '</a>';
              comment += '</div>';
              }
            else{}
            comment += '<p">' + value.comment + '</p>' + '<br>';
            comment += '</div>';
        });
    $('#comment-section').html(comment);
    });
    });

//display form
function replyformdisplay() {
  var postform = '<hr><form id="postform" action="/api/post.php" method="POST" enctype="multipart/form-data">Post: <textarea type="text" class="form-control" name="comment" placeholder="post" required></textarea><p></p>Image: <input name="file" type="file" id="image" /><p></p><input type="hidden" name="commentpostid" value="' + threadid + '"><input name="submit" type="submit" value="Upload" /></form><hr>';
  $('#replyform').html(postform);
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
