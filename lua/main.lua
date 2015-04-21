require("login_system")
local sys = login_system
require("switch_statement")
local case = switch_statement.case
local default = switch_statement.default
local switch = switch_statement.switch
local this =
{
    chat_prefix = "!",
    hook_prefix = "login_system_"
}
hook.Remove("OnPlayerChat", this.hook_prefix .. "OnPlayerChat")
hook.Add("OnPlayerChat", this.hook_prefix .. "OnPlayerChat", function(ply, text)
    if not ply:IsValid() then
        return
    end
    this.temp = text:Trim():gsub("%s+", " ")
    if this.temp:Left(1) ~= this.chat_prefix then
        return
    end
    this.tempe = string.Explode(" ", this.temp)
    if this.tempe[1] == this.chat_prefix then
        if #this.tempe < 2 or #this.tempe[2]:len() < 5 then
            return
        end
        this.tempe[1] = this.tempe[1] .. this.tempe[2]:upper()
    else
        if #this.tempe < 1 then
            return
        end
        this.tempe[1] = this.tempe[1]:upper()
    end
    local community_identifier = ply:SteamID64()
    print(Format("community_identifier=%s", community_identifier))
    print("PrintTable(this):")
    PrintTable(this)
    print("PrintTable(this.tempe):")
    PrintTable(this.tempe)
    switch(this.tempe[1],
        case(this.chat_prefix .. "LOGIN", function()
            print(Format("sys.login(%s):", community_identifier))
            sys.login(community_identifier)
        end, true),
        case(this.chat_prefix .. "LOGOUT", function()
            print(Format("sys.logout(%s):", community_identifier))
            sys.logout(community_identifier)
        end, true),
        case(this.chat_prefix .. "REGISTER", function()
            local name = this.tempe[2]
            print(Format("sys.register(%s, %s):", community_identifier, name))
            sys.register(community_identifier, name)
            print(name)
        end, true))
end)
collectgarbage()
