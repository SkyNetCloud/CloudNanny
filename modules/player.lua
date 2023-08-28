
-- Global Variables
--allowedPlayerArray={["Topher"]=true,["nunley21"]=true,["Demethan"]=true,["waerloga"]=true}

-- pastebin for installer
local installer = "a3Rs0Tzg"
-- players ignored by senors
local allowedPlayerArray={}
-- inventory arrays to compare
local snapShot={}
local snapShot2={}
local itemString={}
-- players currently inside sensor range 
local flag={}
-- counter for scanning animation
local heart = 0
-- counter for phone_home() 
local time = 0
-- user token to send to server
local token = '0'
-- this scanners name
local scanner = ''
-- oweners username on website
local username = ''
-- currently installed version
local version = 2

-- write text to the terminal screen
function draw_text_term(x, y, text, text_color, bg_color)
    term.setTextColor(text_color)
    term.setBackgroundColor(bg_color)
    term.setCursorPos(x,y)
    write(text)
end

-- draw a line on the terminal screen
function draw_line_term(x, y, length, color)
    term.setBackgroundColor(color)
    term.setCursorPos(x,y)
    term.write(string.rep(" ", length))
end

function bars()
	draw_line_term(1, 1, 51, colors.lime)
	draw_line_term(1, 19, 51, colors.lime)
	draw_text_term(20, 1, 'Base Scanner', colors.gray, colors.lime)
	draw_text_term(13, 19, 'cloudnanny.skynetcloud.ca', colors.gray, colors.lime)
end

function scanner_screen()
	term.clear()
	
	-- scanner info
	bars()
	draw_text_term(1, 2, 'Scanner: ', colors.lime, colors.black)
	draw_text_term(10, 2, scanner, colors.white, colors.black)
	draw_text_term(1, 3, 'Owner: ', colors.lime, colors.black)
	draw_text_term(8, 3, username, colors.white, colors.black)
	draw_text_term(1, 4 , string.rep("-", 51), colors.lime, colors.black)
	
	-- scanning graphic
	heart = heart + 1
	draw_text_term(3, 10, 'Scanning ', colors.red, colors.black)
	draw_text_term(12, 10 , string.rep(".", heart), colors.red, colors.black)
	if heart == 15 then
		heart = 1
	end
end

-- retrieves token from local text file
-- called at startup if config.txt exists
-- token is used to authorize the scanner to post to users log
function load_config()
    sr = fs.open("config.txt", "r")
    token = sr.readLine()
	scanner = sr.readLine()
	username = sr.readLine()
    sr.close()
end

-- called for new installations and when the scanner needs to be updated
function run_installer()
    if fs.exists("install") then
        fs.delete("install")
    end
    shell.run("pastebin get "..installer.." install")
    sleep(1)
    shell.run("install")
end

-- called every 30 seconds when scanner is running
-- tells the server that the scanner is online
-- checks version and automtically updates
function phone_home()
    response = http.post("https://cloudnanny.skynetcloud.ca/code/ping.php",
        "token="..token.."&id="..os.getComputerID())
	current_version = response.readAll()

	if tonumber(current_version) > version then
        run_installer()
	end
end

function round(what, precision)
   if what==nil then return 0 end
   return math.floor(what*math.pow(10,precision)+0.5) / math.pow(10,precision)
end

function record() 
	term.setCursorPos(1,1)
	players=s.getOnlinePlayers()

	--Cleanup
	playerData={}
	data={}
	inventory={}
	guilty=nil
end

-- iterate through all players with an active flag
-- see if they're still in range of the scanner
function leaveCheck()  
	for ign,v in pairs(flag) do
	--	print("Did ",ign," leave?")
		local ok,msg=pcall(function ()s.getPlayersInRange(10,ign) end)
		--print(msg) --debug
		if not ok and flag[ign] then
			--print(ign," has left.")
			flag[ign]=false
			post(ign,2," has left sensor range")
		end
	end
end

-- records a log entry on the server
-- passes to server:
	-- token and computer ID (used to verify source)
	-- event type: 1 = player entering, 2 = player leaving, 3 = inventory change
	-- ign, players name
	-- discription of event
	
-- e.g. post('tom', 2, ' has left sensor range')
function post(ign, event, discription)  
    http.post("https://cloudnanny.skynetcloud.ca/code/log.php",
        "token="..token.."&ign="..ign.."&id="..os.getComputerID().."&event="..event.."&discription="..discription)
end

function tablelength(T) --get real count of table
    local count = 0
    for _ in pairs(T) do
        count = count + 1
    end
    return count
end

function compare(t1,t2)
	local ai = {}
    local r = {}
	table.sort(t1) --sorting by name
	table.sort(t2) --sorting by name
	
	for k,v in pairs(t2) do 
		r[k] = v; ai[v]=true 
	end
	for k,v in pairs(t1) do 
		if ai[v]~=nil then   --if item match, remove it from temp table r
			r[k]=nil   
		end
	end
	
	if tablelength(r) > 0 then --if there are items left in r 
		 return true,r
	else
		return false,nil
	end
end

function start_recording()
	-- main loop
	while true do
		-- run scan
		ok,msg=pcall(record)
		leaveCheck()
		
		-- animate screen and delay
		scanner_screen()	
		sleep(0.5)
		scanner_screen()	
		sleep(0.5)
		
		-- main active status with server
		time = time + 1
		if time > 30 then
			time=0
			phone_home()
		end
	end
end

function start()
	s=peripheral.find("playerDetector")
	i=peripheral.find("inventoryManager")
	heart=0
	term.clear()
	term.setCursorPos(1,1)
	
    if fs.exists("config.txt") then
        load_config()
        start_recording()
    else
        run_installer()
    end
end

start()