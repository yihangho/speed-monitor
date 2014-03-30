#!/usr/bin/env ruby
require 'net/http'

ts = Time.now.to_i

speedtest_result_io = IO.popen("/usr/local/bin/speedtest-cli --simple")
ping = speedtest_result_io.gets[/Ping: (\d*.+\d*) ms/, 1]
dl = speedtest_result_io.gets[/Download: (\d*.+\d*) Mbit\/s/, 1]
ul = speedtest_result_io.gets[/Upload: (\d*.+\d*) Mbit\/s/, 1]
key = "123456"

puts "Ts: #{ts}, ping: #{ping}, dl: #{dl}, ul: #{ul}"

uri = URI('http://www.example.com/api/submit.php')
res = Net::HTTP.post_form(uri, timestamp: ts, ping: ping, dl: dl, ul: ul, key: key)
puts res.body