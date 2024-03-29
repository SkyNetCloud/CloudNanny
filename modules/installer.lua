-----------------PASTEBINs--------------------------
installer = "a3Rs0Tzg"
player_module = "5ZWgeGMH"
redstone_module = "jxJuua1f"
fluid_module = "efMuVtTy"
energy_module = "mXX5fU4g"
hash_api = "FLQ68J88"
startup = "KnmEN37h"
---------------------------------------------
term.clear()
local token = ''
local module_name = ''
local username = ''
local type = ''
local updating = false
local user = ''

-- Alternative (and much more versatile) function than "pastebin get"
local function getPaste(id, filename)
    local site = http.get("http://pastebin.com/raw.php?i="..id)
    local content = site.readAll()
    if content then
        local file = fs.open(filename, "w")
        file.write(content)
        file.close()
    else
        -- Unable to connect to Pastebin for whatever reason
        error("Unable to contact Pastebin!")
    end
end

--[[Even better installation function that installs all files
    You'd just need to define which computer is using which module
    or find a way to have each computer use all modules at once
    It is possible, I guarantee it.
    Remove brackets to enable
local function getFiles()
    local files = {
        installer = "Q8ah3K9S",
        player_module = "rWp0GXDW",
        redstone_module = "KkCYWkSU",
        fluid_module = "x7K3zUAC",
        energy_module = "RxLuZWHp",
        hash_api = "FLQ68J88",
        startup = "KnmEN37h"
    }
    for i, v in pairs(files) do
        local site = http.get("http://pastebin.com/raw.php?i="..v)
        local content = site.readAll()
        if content then
            local file = fs.open(i, "w")
            file.write(content)
            file.close()
        else
            -- Unable to connect
        end
    end
end

Alternatively, you can host all of these files on Github, and retrieve them from it too.
Use "https://raw.githubusercontent.com/jaranvil/CloudNanny/master/modules/"..filename
instead of "http://pastebin.com/raw.php?i="..pasteID
to retrieve them. It's very nice to do it that way, considering you can set up an automatic updater.
]]

function draw_text_term(x, y, text, text_color, bg_color)
    term.setTextColor(text_color)
    term.setBackgroundColor(bg_color)
    term.setCursorPos(x,y)
    write(text)
end

function draw_line_term(x, y, length, color)
    term.setBackgroundColor(color)
    term.setCursorPos(x,y)
    term.write(string.rep(" ", length))
end

function bars()
	draw_line_term(1, 1, 51, colors.lime)
	draw_line_term(1, 19, 51, colors.lime)
	draw_text_term(12, 1, 'CloudNanny Module Installer', colors.gray, colors.lime)
	draw_text_term(17, 19, 'cloudnanny.skynetcloud.ca', colors.gray, colors.lime)
end

-- saves current token variable to local text file
function save_config()
    sw = fs.open("config.txt", "w")   
    sw.writeLine(token)
	sw.writeLine(module_name)
	sw.writeLine(username)
	sw.writeLine(type)
    sw.close()
end

function load_config()
    sr = fs.open("config.txt", "r")
    token = sr.readLine()
	module_name = sr.readLine()
	username = sr.readLine()
	type = sr.readLine()
    sr.close()
end

function launch_module()
    shell.run("CN_module")
end

function install_module()
	if type == '1' then
		pastebin = player_module
	elseif type == '2' then
		pastebin = energy_module
	elseif type == '3' then
		pastebin = fluid_module
	elseif type == '4' then
		pastebin = redstone_module
	end
	
	term.clear()
	bars()
	draw_text_term(1, 3, 'successfully logged in', colors.lime, colors.black)
	sleep(0.5)
	draw_text_term(1, 4, 'installing...', colors.white, colors.black)
	sleep(0.5)
	
	draw_text_term(1, 5, 'removing old versions', colors.white, colors.black)
	if fs.exists("CN_module") then
	    fs.delete("CN_module")
	end
	sleep(0.5)
	
	draw_text_term(1, 6, 'fetch from pastebin', colors.white, colors.black)
	term.setCursorPos(1,7)
	term.setTextColor(colors.white)
    getPaste(pastebin, "CN_module")
    
    sleep(0.5)
  
    draw_text_term(1, 9, 'create startup file', colors.white, colors.black)
	term.setCursorPos(1,10)
	term.setTextColor(colors.white)
    if fs.exists("startup") then
        fs.delete("startup")
    end
    getPaste(startup, "startup")
    sleep(1)
  
    draw_text_term(1, 13, 'Setup Complete', colors.lime, colors.black)

    draw_text_term(1, 14, 'press enter to continue', colors.lightGray, colors.black)

    if updating then

    else
        input = read()
    end

    launch_module()
end

function hash(password)
	getPaste(hash_api, "sha1_api")
	os.loadAPI('sha1_api')
	response = http.post(
        "https://cloudnanny.skynetcloud.ca/code/salt.php",
		"user="..user)
	salt = response.readAll()
	hash = sha1_api.sha1(salt..password)
	return hash
end

function login()
	term.clear()
	bars()
	draw_text_term(1, 3, 'Register module to your CloudNanny account.', colors.lime, colors.black)
	draw_text_term(1, 4, 'Create an account at cloudnanny.skynetcloud.ca', colors.lightGray, colors.black)
	
	draw_text_term(1, 6, 'Username: ', colors.lime, colors.black)
	term.setTextColor(colors.white)
	user = read()
	draw_text_term(1, 7, 'Password: ', colors.lime, colors.black)
	term.setTextColor(colors.white)
	pass = read("*")
	

	
	response = http.post(
        "https://cloudnanny.skynetcloud.ca/code/signin.php",
        "user="..user.."&pass="..hash(pass).."&id="..os.getComputerID().."&module_name="..module_name.."&module_type="..module_type)
	token = response.readAll()

	if token == 'error' then
		draw_text_term(1, 8, 'login failed', colors.red, colors.black)
		sleep(2)
		login()
	else 
		username = user
		save_config()
		install_module()
	end
end

function name()
	term.clear()
	bars()
	
	draw_text_term(1, 3, 'Give this module a unique name:', colors.lime, colors.black)
	term.setCursorPos(2,4)
	term.setTextColor(colors.white)
	module_name = read()
	login()
end

function player_tracker()
	
	-- code to check that openperipheral sensor is present. give relavent error
	
	type = '1'
	name()
end


function power_system()
	
	-- code to check that openperipheral sensor is present. give relavent error
	
	type = '2'
	name()
end


function choose_module(input)
	if input == '1' then
		player_tracker()
	elseif input == '2' then
		power_system()
	elseif input == '3' then
		type = '3'
		name()
	elseif input == '4' then
		type = '4'
		name()
	elseif input == '5' then
	
	end
end

function install_select()
	term.clear()
	bars()
	draw_text_term(15, 3, 'Welcome to CloudNanny!', colors.lime, colors.black)
	draw_text_term(1, 5, 'What module would you like to install?', colors.white, colors.black)
	
	draw_text_term(2, 7, '1. Player Tracking', colors.white, colors.black)
	draw_text_term(2, 8, '2. Energy Monitor', colors.white, colors.black)
	draw_text_term(2, 9, '3. Fluid Monitor', colors.white, colors.black)
	draw_text_term(2, 10, '4. Redstone Controls', colors.white, colors.black)
	draw_text_term(2, 11, '5. Rednet Controls', colors.white, colors.black)
	draw_text_term(1, 13, 'Enter number:', colors.white, colors.black)
	term.setCursorPos(1,14)
	term.setTextColor(colors.white)
	input = read()
	
	choose_module(input)
end

function start()
    term.clear()
    if fs.exists("config.txt") then
        load_config()
        updating = true
        install_module()
    else
        install_select()
    end
end

start()