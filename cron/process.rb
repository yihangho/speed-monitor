#!/usr/bin/ruby
require 'net/http'

ts = Time.now.to_i
ping = gets[/Ping: (\d*.+\d*) ms/, 1]
dl = gets[/Download: (\d*.+\d*) Mbit\/s/, 1]
ul = gets[/Upload: (\d*.+\d*) Mbit\/s/, 1]

puts "Ts: #{ts}, ping: #{ping}, dl: #{dl}, ul: #{ul}"

uri = URI('http://www.example.com/api/submit.php')
res = Net::HTTP.post_form(uri, timestamp: ts, ping: ping, dl: dl, ul: ul)
puts res.body