#!/usr/bin/env ruby
require 'net/http'
require 'openssl'

ts = Time.now.to_i
if ENV["SPEED_MONITOR_SERVER_ID"]
  servers = ENV["SPEED_MONITOR_SERVER_ID"].split(',').map { |x| x.strip }
else
  servers = []
end

server_used = nil
results_arr = []

servers.each do |id|
  next if id.empty?
  puts "Trying #{id}"
  speedtest_result_io = IO.popen("/usr/local/bin/speedtest-cli --simple --server #{id}")
  results_arr = speedtest_result_io.read.scan(/\d+\.?\d*/)
  server_used = id
  break if results_arr.length >= 3
end

if results_arr.length < 3
  server_used = "auto"
  puts "Auto"
  speedtest_result_io = IO.popen("/usr/local/bin/speedtest-cli --simple")
  results_arr = speedtest_result_io.read.scan(/\d+\.?\d*/)
end

abort("Speed test failed.") unless results_arr.length >= 3
ping, dl, ul = results_arr

unless ENV["SPEED_MONITOR_SECRET_KEY"].nil?
  key = ENV["SPEED_MONITOR_SECRET_KEY"]
else
  key = "123456"
end

unless ENV["SPEED_MONITOR_URL"].nil?
  uri = URI(ENV["SPEED_MONITOR_URL"])
else
  uri = URI('https://www.example.com/speed-test/api/submit.php')
end

puts "Ts: #{ts}, ping: #{ping}, dl: #{dl}, ul: #{ul}, id: #{server_used}"

if uri.path.empty?
  uri.path = "/"
end

http = Net::HTTP.new(uri.host, uri.port)
http.use_ssl = (uri.scheme == "https")

begin
  response = http.request_post(uri.path, "timestamp=#{ts}&ping=#{ping}&dl=#{dl}&ul=#{ul}&key=#{key}&server=#{server_used}")
  p response
  puts response.body
rescue Exception => e
  puts "Cannot send result to server"
  puts e.message
end
