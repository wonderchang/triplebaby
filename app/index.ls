$ document .ready ->

  # resize
  resize.cover!

  # Login and signup
  $ \#report-btn .click ->
    flag = cookie.check!
    if flag is 0
      location.href = "#{location.href}login.html"
    else if check is 1
      location.href = "#{location.href}report.html"
    else
      location.href = "#{location.href}error.html"

/*
for i to 7
  append-latest-report {
    img: \res/demo1.jpg
    time: "2015/01/01 12:00:00"
    region: \東區
    type: \紅線違規停車
  }

function append-latest-report
  img = $ "<div>" .add-class \report-img
    .append ($ "<img>" .attr \src, it.img)
  time = $ "<div>" .add-class \ui
    .add-class \header
    .add-class \tiny
    .text it.time
  region = $ "<div>" .add-class \ui
    .add-class \gray
    .add-class \label
    .text it.region
  type = $ "<div>" .add-class \ui
    .add-class \red
    .add-class \label
    .text it.type
  column = $ "<div>"
    .add-class \column
    .append-to \#latest-report-content
  $ "<div>" .add-class \ui
    .add-class \segment
    .append img
    .append time
    .append region
    .append type
    .append-to column
*/
