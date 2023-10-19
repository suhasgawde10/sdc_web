<?php
include 'Constants.php';
$version = 6;
class Controller
{
    function connect()
    {

        $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DBNAME);

        if ($mysqli->connect_errno) {
            // echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            // die();
            return false;
        } else {
            return $mysqli;
        }

    }

    function close($mysqli)
    {
        mysqli_close($mysqli);
    }


    //generic methods
    function genericInsertUpdateDelete($query)
    {
        $return = false;
        $mysqli = $this->connect();
        $mysqli->query("set character_set_results='utf8'");
        $result = $mysqli->query($query);
        if ($result) {
            $return = true;
        } else {
            $return = false;
        }
        $this->close($mysqli);
        return $return;
    }

    function multipleInsertUpdateDelete($query)
    {
        $return = false;
        $mysqli = $this->connect();
        $mysqli->query("set character_set_results='utf8'");
        $result = $mysqli->query($query);
        if ($result) {
            $return = true;
        } else {
            $return = false;
        }

        $this->close($mysqli);
        return $return;
    }

    function genericGetLastInsertedId($query)
    {
        $return = 0;
        $mysqli = $this->connect();
        $mysqli->query("set character_set_results='utf8'");
        $result = $mysqli->query($query);
        if ($result) {
            $return = mysqli_insert_id($mysqli);
        }
        $this->close($mysqli);
        return $return;
    }

    function getCleanValue($array)
    {
        $controller = new Controller();
        $pass_value = array();

        foreach ($array as $item) {
                array_push($pass_value, $controller->clean($item));
        }

        return $pass_value;
    }

    function clean($value)
    {
        $mysqli = $this->connect();
        return $mysqli->real_escape_string($value);
    }

    function genericSelectToIterate($query)
    {
        $return = null;
        $mysqli = $this->connect();
        $mysqli->query("set character_set_results='utf8'");
        $result = $mysqli->query($query);
        if ($result->num_rows > 0) {
            $return = $result;
        } else {
            $return = null;
        }
        $this->close($mysqli);
        return $return;
    }

    function genericSelectCount($query)
    {
        $return = 0;
        $mysqli = $this->connect();
        $result = $mysqli->query($query);
        if ($result->num_rows > 0) {
            $return = $result->num_rows;
        } else {
            $return = 0;
        }
        $this->close($mysqli);
        return $return;
    }

    function genericSelectAlreadyIterated($query)
    {
        $return = null;
        $mysqli = $this->connect();
        $mysqli->query("set character_set_results='utf8'");
        $result = $mysqli->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $return = $row;
        } else {
            $return = null;
        }
        $this->close($mysqli);
        return $return;
    }

    function InsertUpdateDelete($query, $types = null, $params = null)
    {
        $return = null;

        $mysqli = $this->connect();
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($types, ...$params);
        if (!$stmt->execute()) {
            $return = false;
        } else {
            $return = true;
        }
        $stmt->close();
        return $return;
    }

    function get_result($sql, $types = null, $params = null)
    {
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) return false;
        return $stmt->get_result();
    }

    /*procedure start*/

    function genericInsertUpdateDeleteUsingProcedure($query, $types = null, $params = null)
    {
        $return = null;
        try {
            $mysqli = $this->connect();
            mysqli_set_charset( $mysqli, 'utf8');
            $stmt = $mysqli->prepare($query);
            $params1 = $this->getCleanValue($params);

            if ($params1 != null) {
                $stmt->bind_param($types, ...$params1);
            }
            if (!$stmt->execute()) {
                $return = false;
            } else {
                $return = true;
            }
            $stmt->close();
            $this->close($mysqli);
        } catch (Exception $e) {
            $return = false;
            $e->getMessage();
        }
        return $return;
    }

    function genericGetLastInsertedIdUsingProcedure($query, $types = null, $params = null)
    {
        $return = 0;
        try {
            $mysqli = $this->connect();
            mysqli_set_charset( $mysqli, 'utf8');
            $stmt = $mysqli->prepare($query);
            $params1 = $this->getCleanValue($params);
            if ($params1 != null) {
                $stmt->bind_param($types, ...$params1);
            }
            if (!$stmt->execute()) {
                $rs2 = $mysqli->query("SELECT @p_out_param as id");
                $row =  $rs2->fetch_object();
                echo $row->id;
                $return = 0;
            } else {

                $return = $mysqli->insert_id;


            }
            $stmt->close();
            $this->close($mysqli);
        } catch (Exception $e) {
            $return = 0;
            $e->getMessage();
        }
        return $return;
    }

    function genericSelectToIterateUsingProcedure($query, $types = null, $params = null)
    {
        $return = null;
        try {
            $mysqli = $this->connect();
            mysqli_set_charset( $mysqli, 'utf8');
            $stmt = $mysqli->prepare($query);
     /*       $params1 = $this->getCleanValue($params);*/
            if ($params != null) {
                $stmt->bind_param($types, ...$params);
            }
            if (!$stmt->execute()) {
                $return = null;
            } else {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $return = $result;
                } else {
                    $return = null;
                }

            }
            $stmt->close();
            $this->close($mysqli);
        } catch (Exception $e) {
            $return = null;
            $e->getMessage();
        }
        return $return;
    }

    function genericSelectCountUsingProcedure($query, $types = null, $params = null)
    {
        $return = null;
        try {
            $mysqli = $this->connect();
            $stmt = $mysqli->prepare($query);
            if ($params != null) {
                $stmt->bind_param($types, ...$params);
            }
            if (!$stmt->execute()) {
                $return = null;
            } else {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $return = $result->num_rows;
                } else {
                    $return = 0;
                }

            }
            $stmt->close();
            $this->close($mysqli);
        } catch (Exception $e) {
            $return = null;
            $e->getMessage();
        }
        return $return;
    }

    function genericSelectAlreadyIteratedUsingProcedure($query, $types = null, $params = null)
    {
        $return = null;
        try {
            $mysqli = $this->connect();
            mysqli_set_charset( $mysqli, 'utf8');
            $stmt = $mysqli->prepare($query);

            if ($params != null) {
                $stmt->bind_param($types, ...$params);
            }
            if (!$stmt->execute()) {

                $return = null;
            } else {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $return = $result->fetch_assoc();
                } else {
                    $return = null;
                }
            }
            $stmt->close();
        } catch (Exception $e) {
            $return = null;
            $e->getMessage();
        }
        return $return;
    }


    /*procedure end*/


}


?>