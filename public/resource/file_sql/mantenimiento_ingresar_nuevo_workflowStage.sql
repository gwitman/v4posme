-- =============================================================================
-- SCRIPT: Insertar nuevo WorkflowStage con relaciones y autorizaciones
-- =============================================================================
-- PARAMETROS:
--   @workflowStageName  VARCHAR  : Nombre del estado  ej: 'ACTIVO', 'ELIMINADO'
--   @workflowID         INT      : ID del workflow al que pertenece el estado
--   @flavorID           INT      : ID del flavor para buscar estados existentes (referencia)
--   @flavorIDTarget     INT      : ID del flavor para insertar el nuevo registro
-- =============================================================================

SET @workflowStageName = 'PROSPECTO';   -- << CAMBIAR SEGUN NECESIDAD
SET @workflowID        = 17;          -- << CAMBIAR SEGUN NECESIDAD
SET @flavorID          = 0;          -- << CAMBIAR SEGUN NECESIDAD (usado para busquedas de estados existentes)
SET @flavorIDTarget    = 439;          -- << CAMBIAR SEGUN NECESIDAD (usado para insertar el nuevo registro)

-- -----------------------------------------------------------------------------
-- BLOQUE 1: Insertar en tb_workflow_stage (si no existe con mismo nombre+flavor)
-- -----------------------------------------------------------------------------

-- Verificar si ya existe el estado con el mismo nombre y flavorIDTarget en el workflowID
SET @existingWorkflowStageID = NULL;

SELECT workflowStageID
INTO   @existingWorkflowStageID
FROM   tb_workflow_stage
WHERE  workflowID = @workflowID
  AND  name       = @workflowStageName
  AND  flavorID   = @flavorIDTarget
LIMIT 1;

-- Si no existe, insertarlo
INSERT INTO tb_workflow_stage (
    componentID,
    workflowID,
    name,
    description,
    display,
    flavorID,
    editableParcial,
    editableTotal,
    eliminable,
    aplicable,
    vinculable,
    isActive,
    isInit
)
SELECT
    ws_ref.componentID,   -- tomar componentID de los estados existentes del mismo workflow
    @workflowID,
    @workflowStageName,
    @workflowStageName,   -- description igual al nombre por defecto
    @workflowStageName,   -- display igual al nombre por defecto
    @flavorIDTarget,      -- usar flavorIDTarget para el nuevo registro
    0,                    -- editableParcial
    1,                    -- editableTotal
    1,                    -- eliminable
    0,                    -- aplicable
    0,                    -- vinculable
    1,                    -- isActive
    1                     -- isInit
FROM   tb_workflow_stage ws_ref
WHERE  ws_ref.workflowID = @workflowID
  AND  ws_ref.flavorID   = @flavorID
-- Solo insertar si NO existe ya
AND NOT EXISTS (
    SELECT 1
    FROM   tb_workflow_stage
    WHERE  workflowID = @workflowID
      AND  name       = @workflowStageName
      AND  flavorID   = @flavorIDTarget
)
LIMIT  1; 

-- Obtener el workflowStageID (sea recien insertado o ya existente)
SET @newWorkflowStageID = NULL;

SELECT workflowStageID
INTO   @newWorkflowStageID
FROM   tb_workflow_stage
WHERE  workflowID = @workflowID
  AND  name       = @workflowStageName
  AND  flavorID   = @flavorIDTarget
LIMIT 1;

-- -----------------------------------------------------------------------------
-- BLOQUE 2: Insertar relaciones en tb_workflow_stage_relation
--   - El nuevo estado puede pasar a todos los estados existentes (origen -> destino)
--   - Todos los estados existentes pueden pasar al nuevo estado (existente -> nuevo)
--   Solo insertar si la relacion NO existe previamente.
-- -----------------------------------------------------------------------------

-- Obtener componentID del workflow para usarlo en las relaciones
SET @componentID = NULL;

SELECT componentID
INTO   @componentID
FROM   tb_workflow_stage
WHERE  workflowID = @workflowID
  AND  flavorID   = @flavorID
  AND  workflowStageID <> @newWorkflowStageID
LIMIT 1;

-- 2A: Nuevo estado (origen) -> cada estado existente (destino)
INSERT INTO tb_workflow_stage_relation (
    componentID,
    workflowID,
    workflowStageID,
    workflowStageTargetID,
    necesitaAuth,
    AuthRolID
)
SELECT
    @componentID,
    @workflowID,
    @newWorkflowStageID,          -- origen: el nuevo estado
    ws_existing.workflowStageID,  -- destino: cada estado existente (buscado por @flavorID)
    0,
    0
FROM   tb_workflow_stage ws_existing
WHERE  ws_existing.workflowID        = @workflowID
  AND  ws_existing.workflowStageID  <> @newWorkflowStageID
  AND NOT EXISTS (
      SELECT 1
      FROM   tb_workflow_stage_relation r
      WHERE  r.workflowID            = @workflowID
        AND  r.workflowStageID       = @newWorkflowStageID
        AND  r.workflowStageTargetID = ws_existing.workflowStageID
  );

-- 2B: Cada estado existente (origen) -> nuevo estado (destino)
INSERT INTO tb_workflow_stage_relation (
    componentID,
    workflowID,
    workflowStageID,
    workflowStageTargetID,
    necesitaAuth,
    AuthRolID
)
SELECT
    @componentID,
    @workflowID,
    ws_existing.workflowStageID,  -- origen: cada estado existente (buscado por @flavorID)
    @newWorkflowStageID,          -- destino: el nuevo estado
    0,
    0
FROM   tb_workflow_stage ws_existing
WHERE  ws_existing.workflowID        = @workflowID
  AND  ws_existing.workflowStageID  <> @newWorkflowStageID
  AND NOT EXISTS (
      SELECT 1
      FROM   tb_workflow_stage_relation r
      WHERE  r.workflowID            = @workflowID
        AND  r.workflowStageID       = ws_existing.workflowStageID
        AND  r.workflowStageTargetID = @newWorkflowStageID
  );

-- 2C: Nuevo estado (origen) -> si mismo (destino)  ej: 211 -> 211
INSERT INTO tb_workflow_stage_relation (
    componentID,
    workflowID,
    workflowStageID,
    workflowStageTargetID,
    necesitaAuth,
    AuthRolID
)
SELECT
    @componentID,
    @workflowID,
    @newWorkflowStageID,   -- origen: el nuevo estado
    @newWorkflowStageID,   -- destino: el mismo nuevo estado
    0,
    0
-- Solo si NO existe ya esa auto-relacion
WHERE NOT EXISTS (
    SELECT 1
    FROM   tb_workflow_stage_relation r
    WHERE  r.workflowID            = @workflowID
      AND  r.workflowStageID       = @newWorkflowStageID
      AND  r.workflowStageTargetID = @newWorkflowStageID
);

-- -----------------------------------------------------------------------------
-- BLOQUE 3: Insertar en tb_component_autorization_detail
--   companyID             = 2  (fijo)
--   componentAutorizationID = 4  (fijo)
--   componentID, workflowID, workflowStageID  <- del registro recien insertado
-- -----------------------------------------------------------------------------

INSERT INTO tb_component_autorization_detail (
    companyID,
    componentAutorizationID,
    componentID,
    workflowID,
    workflowStageID
)
SELECT
    2,                   -- companyID fijo
    4,                   -- componentAutorizationID fijo
    ws.componentID,
    ws.workflowID,
    ws.workflowStageID
FROM   tb_workflow_stage ws
WHERE  ws.workflowStageID = @newWorkflowStageID
  -- Evitar duplicados
  AND NOT EXISTS (
      SELECT 1
      FROM   tb_component_autorization_detail cad
      WHERE  cad.companyID               = 2
        AND  cad.componentAutorizationID = 4
        AND  cad.componentID             = ws.componentID
        AND  cad.workflowID              = ws.workflowID
        AND  cad.workflowStageID         = ws.workflowStageID
  );

-- -----------------------------------------------------------------------------
-- VERIFICACION: Mostrar resultados
-- -----------------------------------------------------------------------------

SELECT 'WorkflowStage insertado/encontrado:' AS info, @newWorkflowStageID AS workflowStageID;

SELECT 'Relaciones del nuevo estado:' AS info;
SELECT r.*, ws_o.name AS origen, ws_d.name AS destino
FROM   tb_workflow_stage_relation r
INNER JOIN tb_workflow_stage ws_o ON ws_o.workflowStageID = r.workflowStageID
INNER JOIN tb_workflow_stage ws_d ON ws_d.workflowStageID = r.workflowStageTargetID
WHERE  r.workflowID = @workflowID
  AND  (r.workflowStageID = @newWorkflowStageID OR r.workflowStageTargetID = @newWorkflowStageID);

SELECT 'Autorizacion insertada:' AS info;
SELECT *
FROM   tb_component_autorization_detail
WHERE  workflowStageID = @newWorkflowStageID
  AND  companyID = 2
  AND  componentAutorizationID = 4;
