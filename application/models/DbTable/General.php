<?php

/**
 * Simple ActiveRecord
 */
class Application_Model_DbTable_General extends Zend_Db_Table_Abstract
{

	/**
	 * The data/db fields of the object
	 * @var object
	 */
	protected $_data;


	/**
	 * Magic access to the data fields
	 * @return mixed
	 */
	public function __get($name) {
		if (!empty($this->_data) && !empty($this->_data->{$name})) {
			return $this->_data->{$name};
		}
		return false;
	}


	/**
	 * Sets the value of a $this->data attribute
	 * @param string $name
	 * @param string $value
	 */
	public function __set($name, $value) {

		// Verifying that it is a valid field
		$fields = array_flip($this->_fields);
		if (!empty($fields[$name])) {
			$this->_data->{$name} = $value;
		}
	}


	/**
	 * Obtains a row
	 * @param integer|array $value Depending on the PRIMARY KEY or a UNIQUE KEY
	 */
    public function get($filter = false)
    {

    	// Default key to filter
    	$key = 'id';

    	// Filtering by a UNIQUE KEY (only the first one)
    	if (is_array($filter)) {
    		$value = reset($filter);
    		$key = key($filter);

        // Getting it from $this
    	} else if (!$filter) {
    		$value = (int)$this->_data->id;

    	// Filtered by id
    	} else {
    		$value = (int)$filter;
    	}

    	$value = addslashes($value);
        $row = $this->fetchRow($key . " = '" . $value . "'");
        if (!$row) {
        	return false;
        }

        // Store it in the data attribute
        $this->_data = (Object)$row->toArray();

        return $this->_data;
    }

    /**
     * Inserts/Updates the object into DB
     * @param array $data
     * @return integer Object id
     */
    public function save($data = false)
    {
    	// Getting from the object
    	if (!$data) {
    		$data = (Array)$this->_data;
    	}

        $this->filterFields($data);

        if (empty($data['id'])) {
        	$data['id'] = $this->insert($data);
        } else {
        	parent::update($data, 'id = ' . (int)$data['id']);
        }

        $this->_data = (Object)$data;

        return $this->_data->id;
    }


    /**
     * Deletes the object
     * @param integer|string $id
     */
    public function delete($id = false)
    {
    	// Getting the id from the attribute
    	if (!$id) {
    		$id = $this->_data->id;
    	}
        parent::delete('id = ' . (int)$id);

        // Removing the object data
        unset($this->_data);
    }

    /**
     * Ensures only the table fields are being used
     * @param array $data
     * @return array
     */
    protected function filterFields($data)
    {
    	$fields = array_flip($this->_fields);

    	if ($data) {
	    	foreach ($data as $key => $value) {
	    		if (empty($fields[$key])) {
	    			unset($data[$key]);
	    		}
	    	}
    	}

    	return $data;
    }

}
