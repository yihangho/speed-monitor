#!/usr/bin/env ruby
require 'net/http'
require 'openssl'

ts = Time.now.to_i

speedtest_result_io =
  if ENV["SPEED_MONITOR_SERVER_ID"]
    IO.popen("/usr/local/bin/speedtest-cli --simple --server #{ENV["SPEED_MONITOR_SERVER_ID"]}")
  else
    IO.popen("/usr/local/bin/speedtest-cli --simple")
  end

results_arr = speedtest_result_io.scan(/\d+\.?\d*/)
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
