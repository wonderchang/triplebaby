if 1 isnt cookie.check! then location.href = path.dirname!+\login.html

$ window .resize !->
  $ '.grid' .remove-class 'column one two four'
  if 840 < window.inner-width
    $ '#report .grid' .add-class 'four column'
  else if 480 <= window.inner-width <= 840
    $ '#report .grid' .add-class 'two  column'
  else if window.inner-width < 480
    $ '#report .grid' .add-class 'one  column'
.resize!

$ document .ready ->

  if 0 is parseInt location.search.1
    $ '#after-login-setting-list'
      .sidebar \setting, \transition, \push
      .sidebar \toggle, duration: 200

  $.ajax do
    url: \php/get-self-report.php
    type: \POST
    data: cookie: cookie.get!
    success: ->
      for i in JSON.parse it
        append-report do
          img: i.picture1
          time: i.report_time
          region: i.region_name
          subject: i.subject_name
          content: i.content
          progress: i.progress_id
          case: i.case_id
          result-time: i.result_time
          result: i.result

# vi:ei:ft=ls:nowrap:sw=2:ts=2
