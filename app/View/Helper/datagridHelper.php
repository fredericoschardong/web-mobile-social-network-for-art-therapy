<?php
class DatagridHelper extends AppHelper
{
    const ACTION_ADD    = 1;
    const ACTION_EDIT   = 2;
    const ACTION_DELETE = 3;
    const ACTION_UPDOWN = 4;

    public $columns;
    public $actions = array();
    public $data;
    public $actionParams = array();
    public $helpers = array('time', 'paginator', 'html', 'session', 'form', 'lightbox');

    function __construct()
    {
        $this->setActions(array(self::ACTION_ADD, self::ACTION_EDIT, self::ACTION_DELETE));
    }

    function setActions($actions)
    {
        $this->actions = $actions;
    }

    function addAction($action)
    {
        $this->actions[] = $action;
    }
    
    function disableAction($action)
    {
        if ($this->actions)
        {
            foreach ($this->actions as $i => $_act)
            {
                if ($action == $_act)
                {
                    unset($this->actions[$i]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Parameters to include on action URL's
     *
     */
    function setActionParams($params)
    {
        $this->actionParams = $params;
    }

    function processActionParams($values)
    {
        if (!isset($values))
        {
            return;
        }

        $out = array();
        foreach ($this->actionParams as $p)
        {
            if (isset($values[$p]))
            {
                $out[] = $values[$p];
            }
            else
            {
                $out[] = 'null';
            }
        }

        return implode('/', $out);
    }

    function isEnabledAction($action)
    {
        return in_array($action, $this->actions);
    }

    function setColumns($columns)
    {
        $this->columns = $columns;
    }

    /**
     * @example addColumn('Event.eventId', 'Codigo', array('sort'=>true, '');
     */
    function addColumn($column)
    {
        $this->columns[] = $column;
    }

    function setData($data)
    {
        $this->data = $data;
    }

    function getData()
    {
        return $this->data;
    }

    function render()
    {
        foreach ($this->columns as $col)
        {
            if (!isset($col[2])) $col[2] = null;

            list($ref, $label, $opts) = $col;
            list($table, $column) = explode('.', $ref);

            if (!isset($opts['sort'])) $opts['sort'] = null;
            if (!isset($opts['format'])) $opts['format'] = null;

            $sortBy = ($s = $opts['sort']) ? $s : $ref;
            $dataCols[] = '<th>' . $this->paginator->sort($label, $sortBy) . '</th>';

            foreach ($this->data as $i => $v)
            {
                $value = $v[$table][$column];
                if ($opts['format'] == 'date')
                {
                    $value = $this->time->format('d/m/Y H:i', $value);
                }
                if (isset($opts['breakline']))
                {
                    $value = str_replace("\n", "<br>", $value);
                }
                if (isset($opts['textsize']))
                {
                    $value = substr($value, 0, $opts['textsize']) . (strlen($value) > $opts['textsize'] ? '...' : '');
                }
                if (isset($opts['callback']))
                {
                    $value = call_user_func_array($opts['callback'], array($value, $v, $this));
                }
                
                if (!isset($dataValues[$i]))
                {
                    $acts = null;
                    //if ($this->session->check('Auth.User.id'))
                    {
                        if ($this->isEnabledAction(self::ACTION_EDIT))
                        {
                            /** AJAX
                            $goto = array('controller' => $this->params['controller'], 'action' => 'edit', $this->processActionParams($v[$table]));
                            $url = $this->ajaxutil->link('Editar', $goto, array('update' => 'divForm'));
                            $acts .= $url;*/
                            $url = array('controller' => $this->params['controller'], 'action' => 'edit', $this->processActionParams($v[$table]));
                            $acts .= $this->html->image('edit.png', array('alt'=>'Editar', 'title'=>'Editar', 'url'=> $url));
                        }
                        if ($this->isEnabledAction(self::ACTION_DELETE))
                        {
                            $url = '/' . $this->params['controller'] . '/delete/' . $this->processActionParams($v[$table]);
                            $acts .= $this->html->image('delete.png', array('alt'=>'Remover', 'title'=>'Remover', 'url'=> "{$url}", 'onclick' => "return confirm('Confirma remocao?')"));
                        }
                        if ($this->isEnabledAction(self::ACTION_UPDOWN))
                        {
                            $url = '/' . $this->params['controller'] . '/action_up/' . $this->processActionParams($v[$table]);
                            $acts .= $this->html->image('up-16x16.png', array('alt'=>'Subir', 'title'=>'Subir', 'url'=>$url));
                            
                            $url = '/' . $this->params['controller'] . '/action_down/' . $this->processActionParams($v[$table]);
                            $acts .= $this->html->image('down-16x16.png', array('alt'=>'Descer', 'title'=>'Descer', 'url'=>$url));
                        }
                    }

                    //TODO VIEW ACTION $html->image('view.png', array('alt'=>'Visualizar evento', 'title'=>'Visualisar evento', 'url'=>"/events/view/{$v['Event']['eventId']}"))

                    //if ($acts)
                    {
                        $dataValues[$i] = '<td><center>' . ($acts ? $acts : '-') . '<center></td>';
                    }
                }
                $align = isset($opts['align']) ? " align=\"{$opts['align']}\"" : '';
                $dataValues[$i] .= "<td{$align}>" . $value . '</td>';
            }
        }

        //Show messages (inserido, editado...)
        //$this->session->flash();

        if ($this->isEnabledAction(self::ACTION_ADD))
        {
            $url = array('controller'=>$this->params['controller'], 'action'=>'add');
            $img = $this->html->image('add.jpg', array('text'=>'Novo registro'));
            echo $this->html->link("{$img} Novo registro", $url, null, null, false);
        }

        ?>
        <table class="grid">
	        <tr>
                <th width="20%">Acoes</th>
                <?php echo implode('', $dataCols); ?>
	        </tr> 
            <?php foreach ($dataValues as $i => $v): ?>
	        <tr class="<?php echo ($i%2 == 0) ? 'alt' : ''; ?>">
                <?php echo $v; ?>
	        </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->paginator->numbers(); ?>
        <div id="divForm" style="background-color: #B8B8B8"></div>
        <?php
    } 
}
?>
