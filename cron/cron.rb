#!/usr/bin/env ruby
require 'net/http'
require 'openssl'

ts = Time.now.to_i

speedtest_result_io = IO.popen("/usr/local/bin/speedtest-cli --simple")
ping = speedtest_result_io.gets[/Ping: (\d*.+\d*) ms/, 1]
dl = speedtest_result_io.gets[/Download: (\d*.+\d*) Mbit\/s/, 1]
ul = speedtest_result_io.gets[/Upload: (\d*.+\d*) Mbit\/s/, 1]
key = "123456"

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

puts "Ts: #{ts}, ping: #{ping}, dl: #{dl}, ul: #{ul}"

if uri.path.empty?
  uri.path = "/"
end

http = Net::HTTP.new(uri.host, uri.port)
http.use_ssl = (uri.scheme == "https")

begin
  response = http.request_post(uri.path, "timestamp=#{ts}&ping=#{ping}&dl=#{dl}&ul=#{ul}&key=#{key}")
  p response
  puts response.body
rescue Exception => e
  puts "Cannot send result to server"
  puts e.message
end
