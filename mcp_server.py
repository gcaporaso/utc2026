from mcp.server.fastmcp import FastMCP
from huggingface_hub import list_models
import ifcopenshell

mcp = FastMCP("hub-ifc-server")


@mcp.tool()
def get_hf_models(limit: int = 5) -> list[str]:
    """Restituisce un elenco di modelli dal HuggingFace Hub."""
    models = list(list_models(limit=limit))
    return [m.id for m in models]


@mcp.tool()
def count_ifc_entities(ifc_path: str) -> dict:
    """Conta il numero di IfcBuildingElement in un file IFC."""
    try:
        model = ifcopenshell.open(ifc_path)
        return {"entities": len(model.by_type("IfcBuildingElement"))}
    except Exception as e:
        return {"error": str(e)}


@mcp.tool()
def list_ifc_walls(ifc_path: str) -> dict:
    """Restituisce i GlobalId delle IfcWall presenti in un file IFC."""
    try:
        model = ifcopenshell.open(ifc_path)
        walls = model.by_type("IfcWall")
        return {"walls": [w.GlobalId for w in walls]}
    except Exception as e:
        return {"error": str(e)}


if __name__ == "__main__":
    mcp.run()
