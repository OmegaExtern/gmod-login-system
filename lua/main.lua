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
    this.tempe = this.temp:Explode(" ")
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
    switch(this.tempe[1],
        case(this.chat_prefix .. "LOGIN", function()
            sys.login(community_identifier)
        end, true),
        case(this.chat_prefix .. "LOGOUT", function()
            sys.logout(community_identifier)
        end, true),
        case(this.chat_prefix .. "REGISTER", function()
            local name = this.tempe[2]
            sys.register(community_identifier, name)
        end, true))
end)
collectgarbage()
