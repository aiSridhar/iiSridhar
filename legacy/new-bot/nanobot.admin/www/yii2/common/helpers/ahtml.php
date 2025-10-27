<?php

namespace app\common\helpers;


class ahtml
{
    public static function messages_in_counts($data)
    {
        return "<a href ='/admin/messages-in-group/index?MessagesInGroupSearch%5Bclass_id%5D=" . $data["id"] . "'><img src='/admin/imgs/f/navigation-270-white.png'></a> " . $data->get_count_messages_in_group_today() . "<span class='text-muted small'>/" . $data->get_count_messages_in_group_yesterday() . "/" . $data->get_count_messages_in_group_total() . "</span><br>";
    }

    public static function messages_out_counts($data)
    {
        return "<a href ='/admin/messages-out/index?MessagesOutSearch%5Bclass_id%5D=" . $data["id"] . "'><img src='/admin/imgs/f/navigation-090.png'></a> " . $data->get_count_messages_out_today() . "<span class='text-muted small'>/" . $data->get_count_messages_out_yesterday() . "/" . $data->get_count_messages_out_total() . "</span><br>";
    }

    public static function dialogs_counts($data)
    {
        return "<a href ='/admin/messages-out/index?MessagesInGroupSearch%5Bclass_id%5D=" . $data["id"] . "'><img src='/admin/imgs/f/balloons.png'></a> " . $data->get_count_dialogs_today() . "<span class='text-muted small'>/" . $data->get_count_dialogs_yesterday() . "/" . $data->get_count_dialogs_total() . "</span><br>";
    }

    public static function messages_in_noclass_counts($data)
    {
        return "<a href ='/admin/messages-in-group/index?MessagesInGroupSearch%5Bscript_id%5D=".$data["id"]."&MessagesInGroupSearch%5Bclass_id%5D=-1'><img src='/admin/imgs/f/cross-white.png'></a> " . $data->get_count_messages_in_noclass_today() . "<span class='text-muted small'>/" . $data->get_count_messages_in_noclass_yesterday() . "/" . $data->get_count_messages_in_noclass_total() . "</span><br>";
    }

    public static function ca_ready_counts($data)
    {
        return "<img src='/admin/imgs/f/animal-monkey.png'></a> " . $data->get_count_ca_ready() . "<br>";
    }


    public static function script_id($data)
    {
        return "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["script_id"] . "'><img src='/admin/imgs/f/script-block.png'>" . $data["script_id"] . "</a><br>";
    }

    public static function step($data)
    {
        if($data["step"]==-2)
            return "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["script_id"] . "&ClassesSearch%5Bstep%5D=".$data["step"]."'><img src='/admin/imgs/f/globe.png'>" . $data["step"] . "<span class='text-muted small'>/" . $data["priority"] . "</span></a><br>";
        if($data["step"]==-1)
            return "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["script_id"] . "&ClassesSearch%5Bstep%5D=".$data["step"]."'><img src='/admin/imgs/f/arrow-in-out.png'>" . $data["step"] . "<span class='text-muted small'>/" . $data["priority"] . "</span></a><br>";
        if($data["step"]==0)
            return "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["script_id"] . "&ClassesSearch%5Bstep%5D=".$data["step"]."'><img src='/admin/imgs/f/hand-shake.png'>" . $data["step"] . "<span class='text-muted small'>/" . $data["priority"] . "</span></a><br>";

        return "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["script_id"] . "&ClassesSearch%5Bstep%5D=".$data["step"]."'><img src='/admin/imgs/f/arrow-curve-000-left.png'>" . $data["step"] . "<span class='text-muted small'>/" . $data["priority"] . "</span></a><br>";

    }


    public static function actions($data)
    {
        $res="";


        if ($data["action_set_script_id"] > 0)
            $res .= "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["action_set_script_id"] . "'><img src='/admin/imgs/f/script-block.png'>" . $data["action_set_script_id"] . "</a><br>";
        if ($data["action_set_step"] > 0)
            $res .= "<a href ='/admin/classes/index?ClassesSearch%5Bid%5D=&ClassesSearch%5Bname%5D=&ClassesSearch%5Bscript_id%5D=" . $data["action_set_script_id"] . "&ClassesSearch%5Bstep%5D=".$data["action_set_step"]."'><img src='/admin/imgs/f/arrow-curve-000-left.png'>" . $data["action_set_step"]."</a><br>";
        if ($data["action_set_list_id"] > 0)
            $res .= "<a href ='/admin/scripts/update?id=" . $data["script_id"] . "'><img src='/admin/imgs/f/table.png'>" . $data["action_set_list_id"] . "</a><br>";

        if ($data["action_exec_next"] > 0)
            $res .= "<img src='/admin/imgs/f/fire.png'><br>";
        return $res;
    }

    public static function ifs($data)
    {
        $res="";

        if ($data["if_list_id"] > 0)
            $res .= "<a href ='/admin/scripts/update?id=" . $data["script_id"] . "'><img src='/admin/imgs/f/table--plus.png'></a>" . $data["if_list_id"] . "<br>";

        if ($data["if_not_list_id"] > 0)
            $res .= "<a href ='/admin/scripts/update?id=" . $data["script_id"] . "'><img src='/admin/imgs/f/table--minus.png'></a>" . $data["if_not_list_id"] . "<br>";

        if ($data["if_platform"] == "desk")
            $res .= "<img src='/admin/imgs/f/computer.png'><br>";

        if ($data["if_platform"] == "mob")
            $res .= "<img src='/admin/imgs/f/mobile.png'><br>";

        if ($data["if_platform"] == "android")
            $res .= "<img src='/admin/imgs/f/android.png'><br>";

        if ($data["if_platform"] == "ios")
            $res .= "<img src='/admin/imgs/f/mac-os.png'><br>";

        if ($data["if_male"] == "0")
            $res .= "<img src='/admin/imgs/f/toilet-female.png'><br>";

        if ($data["if_male"] == "1")
            $res .= "<img src='/admin/imgs/f/toilet-male.png'><br>";

        if ($data["action_stay"] == "1")
            $res .= "<img src='/admin/imgs/f/arrow-stop.png'><br>";

        return $res;
    }


    public static function engine($data)
    {
        $res = "";
        if ($data["engine"] == "vk")
            $res .= "<img src='/admin/imgs/nav/vk.ico' width='16px' height='16px'><br>";
        elseif ($data["engine"] == "ok")
            $res .= "<img src='/admin/imgs/nav/ok.ico' width='16px' height='16px'><br>";
        elseif ($data["engine"] == "inst")
            $res .= "<img src='/admin/imgs/nav/inst.ico' width='16px' height='16px'><br>";


        return $res;
    }

    public static function enabled($data)
    {
        if($data["enabled"]!=1)
            return "<img src='/admin/imgs/f/light-bulb-off.png'><br>";
        else
            return "<img src='/admin/imgs/f/light-bulb.png'><br>";
    }

    public static function menu($data)
    {
            $res="";

            $res.=self::enabled($data);
            $res.= "<img src='/admin/imgs/f/pencil-small.png'><br>";

            $res.= "<img src='/admin/imgs/f/cross-script.png'><br>";
            return $res;
    }


    public static function classes($data)
    {
        return "<a href ='/admin/classes/index?ClassesSearch%5Bscript_id%5D=".$data["id"]."' alt='classes'><img src='/admin/imgs/f/scripts-text.png'></a> ".$data->get_count_classes()." <br>";
    }


    public static function account_out($data)
    {
        return "<small><a href ='/admin/messages-out/index?MessagesOutSearch%5Bid%5D=&MessagesOutSearch%5Bscript_id%5D=&MessagesOutSearch%5Bstep%5D=&MessagesOutSearch%5Baccount_id%5D=".$data["account_id"]."&MessagesOutSearch%5Bca_id%5D=&MessagesOutSearch%5Bengine%5D='><img src='/admin/imgs/f/robot.png'>".$data["account_id"]."</a></small><br>";
    }

    public static function ca_out($data)
    {

        $res= "<small><a href ='/admin/messages-out/index?MessagesOutSearch%5Bid%5D=&MessagesOutSearch%5Bscript_id%5D=&MessagesOutSearch%5Bstep%5D=&MessagesOutSearch%5Baccount_id%5D=&MessagesOutSearch%5Bca_id%5D=".$data["ca_id"]."&MessagesOutSearch%5Bengine%5D='>";
        /*
        if ($data["if_male"] == "0")
            $res .= "<img src='/admin/imgs/f/toilet-female.png'>";
        elseif ($data["if_male"] == "1")
            $res .= "<img src='/admin/imgs/f/toilet-male.png'>";
        else
            */
            $res.="<img src='/admin/imgs/f/user.png'>";
        $res.=$data["ca_id"]."</a></small><br>";
        return $res;
    }



}