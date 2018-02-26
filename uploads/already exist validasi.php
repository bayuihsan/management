
                        echo "This MSISDN Is Already Exists !!!!"; 
                    else if(!value_exists('table','kolom',$msisdn)){
                        $this->db->insert